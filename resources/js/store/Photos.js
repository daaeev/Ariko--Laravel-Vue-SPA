import axios from "axios";

export default {
    state: {
        works: [],
        single: {},
        pageSize: 6,
        totalPagesCount: null,
        pagPage: null,
        isWorksLoading: false,
        nextSingle: null,
        prevSingle: null,
    },

    getters: {
        isWorksLoading(state) {
            return state.isWorksLoading;
        },

        pagPage(state) {
            return state.pagPage;
        },

        pageSize(state) {
            return state.pagPage;
        },

        totalPagesCount(state) {
            return state.totalPagesCount;
        },

        works(state) {
            return state.works;
        },

        single(state) {
            return state.single;
        },

        singleFromWorksState(state) {
            return work_id => state.works.find((w) => w.id == work_id);
        },

        nextSingle(state) {
            return state.nextSingle;
        },

        prevSingle(state) {
            return state.prevSingle;
        },
    },

    mutations: {
        setIsWorksLoading(state, value) {
            state.isWorksLoading = value;
        },

        addWorks(state, newWorks) {
            state.works = [...state.works, ...newWorks];
        },

        setTotalPagesCount(state, value) {
            state.totalPagesCount = value;
        },

        incrementPagPage(state) {
            if (state.pagPage === null) {
                state.pagPage = 1;
            } else {
                state.pagPage++;
            }
        },

        setSingle(state, work) {
            state.single = work;
        },

        setImagesToSingle(state, images) {
            state.single.images = images;
        },

        setNextSingle(state, value) {
            state.nextSingle = value;
        },

        setPrevSingle(state, value) {
            state.prevSingle = value;
        },
    },

    actions: {
        /**
         * Загрузить работы при помощи АПИ
         *
         * @param commit
         * @param getters
         * @returns {Promise<void>}
         */
        async fetchWorks({commit, getters}) {
            commit('setIsWorksLoading', true);

            const res = await axios.get(
                "http://ariko.lrvl/api/works/photos",
                {
                    params: {
                        _limit: getters.pageSize,
                        page: getters.pagPage ?? 1,
                    },
                }
            );

            if (getters.totalPagesCount === null) {
                commit('setTotalPagesCount', res.data.last_page);
            }
            commit('addWorks', res.data.data);
            commit('incrementPagPage');
            commit('setIsWorksLoading', false);
        },

        /**
         * Несколько операций для получения работы с идентификатором work_id
         * 1) Загружена ли раннее запрашиваемая работа (state.single)
         *
         * 2) Проверяется наличие работы в массиве загруженных работ (state.works)
         *
         * 3) Работа запрашивается у АПИ (actions.fetchWorkById)
         * Если работа не найдена -> редирект на страницу с ошибкой (actions.${app/errorPage})
         *
         * @param commit
         * @param getters
         * @param dispatch
         * @param work_id
         */
        facadeFetchWorkById({commit, getters, dispatch}, work_id) {
            if (work_id == getters.single.id) {
                return;
            }

            let work = getters.singleFromWorksState(work_id);

            if (work) {
                commit('setSingle', work);

                if (!work.images) {
                    dispatch('fetchImagesByWorkId', work_id);
                }

                dispatch('fetchNextPrevIds', work_id);

                return;
            }

            dispatch('fetchWorkById', work_id);
        },

        /**
         * Получить работу с идентификатором work_id
         * Если работа не найдена -> редирект на страницу с ошибкой (actions.${app/errorPage})
         *
         * @param commit
         * @param dispatch
         * @param work_id
         * @returns {Promise<void>}
         */
        async fetchWorkById({commit, dispatch}, work_id) {
            commit('setSingle', {});

            const res = await axios.get(
                `http://ariko.lrvl/api/works/photos/${work_id}`
            );

            if (Object.keys(res.data) == 0) {
                dispatch('app/errorPage', null, {root: true});
                return;
            }

            commit('setSingle', res.data);
            dispatch('fetchNextPrevIds', work_id);
        },

        /**
         * Получение всех фотографий работы с идентификатором work_id
         * Если фотографии не найдены -> редирект на страницу с ошибкой (actions.${app/errorPage})
         *
         * @param commit
         * @param dispatch
         * @param work_id
         * @returns {Promise<void>}
         */
        async fetchImagesByWorkId({commit, dispatch}, work_id) {
            const res = await axios.get(
                `http://ariko.lrvl/api/works/photos/images/${work_id}`
            );

            if (res.data.length == 0) {
                dispatch('app/errorPage', null, {root: true});
                return;
            }

            commit('setImagesToSingle', res.data);
        },

        /**
         * Получить идентификаторы 'следующей' и 'предыдущей' работы
         * @param commit
         * @param work_id
         * @returns {Promise<void>}
         */
        async fetchNextPrevIds({commit}, work_id) {
            const res = await axios.get(
                `http://ariko.lrvl/api/works/photos/next/prev/${work_id}`
            );

            if (res.data.next) {
                commit("setNextSingle", res.data.next.id);
            } else {
                commit("setNextSingle", null);
            }

            if (res.data.prev) {
                commit("setPrevSingle", res.data.prev.id);
            } else {
                commit("setPrevSingle", null);
            }
        }
    },

    namespaced: true,
};
