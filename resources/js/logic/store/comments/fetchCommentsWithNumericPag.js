import SiteSettings from '../../../SiteSettings';
import commentsAPI from '../../api/admin/Comments';

export default {
    state: {
        pagPage: null,
        totalPagesCount: null,
        pageSize: SiteSettings.adminCatalog,
    },

    getters: {
        pagPage(state) {
            return state.pagPage;
        },

        pageSize(state) {
            return state.pageSize;
        },

        totalPagesCount(state) {
            return state.totalPagesCount;
        },
    },

    mutations: {
        setTotalPagesCount(state, value) {
            state.totalPagesCount = value;
        },

        setPagPage(state, page) {
            state.pagPage = page;
        },
    },

    actions: {
        /**
         * Загрузить комментарии при помощи АПИ
         *
         * @param commit
         * @param getters
         * @param dispatch
         * @param page
         * @returns {Promise<void>}
         */
         async fetchComments({ commit, getters, dispatch }, page = 1) {
            commit('setPagPage', page);

            await commentsAPI.fetchComments(
                getters.pageSize,
                page,
                axiosRes => {
                    commit('setComments', axiosRes.data.data);

                    if (getters.totalPagesCount === null) {
                        commit('setTotalPagesCount', axiosRes.data.last_page);
                    }
                },
                axiosError => {
                    console.log('Fetch comments error; ' + axiosError)
                    dispatch('app/errorPage', null, { root: true });
                }
            );
        },
    },
};
