import FetchPhotosWorks from "../../api/PhotosWorks";

export default {
    state: {
        single_pagination: {
            next_id: null,
            prev_id: null,
        },
    },

    getters: {
        singleFromWorksState(state) {
            return work_id => state.works.find((w) => w.id == work_id);
        },
    },

    mutations: {
        setSingle(state, work) {
            state.single = work;
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
    }
};
