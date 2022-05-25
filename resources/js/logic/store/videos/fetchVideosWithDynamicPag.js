import FetchVideosWorks from "../../../logic/api/VideosWorks";
import SiteSettings from "../../../SiteSettings";

export default {
    state: {
        pageSize: SiteSettings.videosPerPage,
        isWorksLoading: false,
        pagPage: 0,
        totalPagesCount: null,
    },

    getters: {
        isWorksLoading(state) {
            return state.isWorksLoading;
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

        pageSize(state) {
            return state.pageSize;
        },
    },

    mutations: {
        addWorks(state, newWorks) {
            state.works = [...state.works, ...newWorks];
        },

        setIsWorksLoading(state, value) {
            state.isWorksLoading = value;
        },

        incrementPagPage(state) {
            state.pagPage++;
        },

        setTotalPagesCount(state, value) {
            state.totalPagesCount = value;
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
            commit('incrementPagPage');

            await FetchVideosWorks.fetchWorks(
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

            commit('setIsWorksLoading', false);
        },
    }
};
