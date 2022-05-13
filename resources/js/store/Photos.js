import FetchPhotosWorks from "../logic/FetchPhotosWorks";

export default {
    state: {
        works: [],
        single: {},
        isWorksLoading: false,

        pageSize: 6,
        pagPage: null,
        totalPagesCount: null,
        single_pagination: {
            next_id: null,
            prev_id: null,
        },
    },

    getters: {
        isWorksLoading(state) {
            return state.isWorksLoading;
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

        pageSize(state) {
            return state.pageSize;
        },

        pagPage(state) {
            return state.pagPage;
        },

        totalPagesCount(state) {
            return state.totalPagesCount;
        },

        singlePagination(state) {
            return state.single_pagination;
        },
    },

    mutations: {
        setIsWorksLoading(state, value) {
            state.isWorksLoading = value;
        },

        addWorks(state, newWorks) {
            state.works = [...state.works, ...newWorks];
        },

        setSingle(state, work) {
            state.single = work;
        },

        incrementPagPage(state) {
            if (state.pagPage === null) {
                state.pagPage = 1;
            } else {
                state.pagPage++;
            }
        },

        setTotalPagesCount(state, value) {
            state.totalPagesCount = value;
        },

        setSinglePagNext(state, value) {
            state.single_pagination.next_id = value;
        },

        setSinglePagPrev(state, value) {
            state.single_pagination.prev_id = value;
        },
    },

    actions: {
        /**
         * Загрузить работы при помощи АПИ
         *
         * @param commit
         * @param getters
         * @param dispatch
         * @returns {Promise<void>}
         */
        async fetchWorks({commit, getters, dispatch}) {
            commit('setIsWorksLoading', true);

            await FetchPhotosWorks.fetchWorks(
                getters.pageSize,
                getters.pagPage,
                axiosRes => {
                    commit('addWorks', axiosRes.data.data);

                    if (getters.totalPagesCount === null) {
                        commit('setTotalPagesCount', axiosRes.data.last_page);
                    }
                },
                axiosError => {
                    console.log('Fetch works error; ' + axiosError)
                    dispatch('app/errorPage', null, {root: true});
                }
            );

            commit('incrementPagPage');
            commit('setIsWorksLoading', false);
        },

        /**
         * Несколько операций для получения работы с идентификатором work_id
         * 1) Загружена ли раннее запрашиваемая работа (getters.single)
         *
         * 2) Проверяется наличие работы в массиве загруженных работ (getters.works)
         *
         * 3) Работа запрашивается у АПИ (FetchPhotosWorks.fetchWorkById)
         *
         * @param getters
         * @param commit
         * @param dispatch
         * @param work_id
         */
        async fetchWorkById({getters, commit, dispatch}, work_id) {

            // Загружена ли раннее запрашиваемая работа (getters.single)
            if (work_id == getters['single'].id) {
                return;
            }

            // Проверяется наличие работы в массиве загруженных работ (getters.works)
            const work = getters['singleFromWorksState'](work_id);

            if (work) {
                commit('setSingle', work);

                await FetchPhotosWorks.fetchNextPrevIds(
                    work.id,
                    axiosRes => {
                        commit('setSinglePagNext', (axiosRes.data.next ? axiosRes.data.next.id : null));
                        commit('setSinglePagPrev', (axiosRes.data.prev ? axiosRes.data.prev.id : null));
                    },
                    axiosError => {
                        console.log('Fetch next/prev works error; ' + axiosError)
                        dispatch('app/errorPage', null, {root: true});
                    }
                );

                return;
            }

            // Работа запрашивается у АПИ (FetchPhotosWorks.fetchWorkById)
            await FetchPhotosWorks.fetchWorkById(
                work_id,
                async axiosRes => {
                    const work = axiosRes.data;

                    if (Object.keys(work) == 0) {
                        dispatch('app/errorPage', null, {root: true});
                        return;
                    }

                    await FetchPhotosWorks.fetchNextPrevIds(
                        work.id,
                        axiosRes => {
                            commit('setSinglePagNext', (axiosRes.data.next ? axiosRes.data.next.id : null));
                            commit('setSinglePagPrev', (axiosRes.data.prev ? axiosRes.data.prev.id : null));
                        },
                        axiosError => {
                            console.log('Fetch next/prev works error; ' + axiosError)
                            dispatch('app/errorPage', null, {root: true});
                        }
                    );

                    commit('setSingle', work);
                },
                axiosError => {
                    console.log('Fetch work by id error; ' + axiosError)
                    dispatch('app/errorPage', null, {root: true});
                }
            );
        }
    },

    namespaced: true,
};
