import SiteSettings from '../../../SiteSettings';
import messagesAPI from '../../api/admin/Messages';

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
         * Загрузить письма при помощи АПИ
         *
         * @param commit
         * @param getters
         * @param dispatch
         * @param page
         * @returns {Promise<void>}
         */
         async fetchMessages({ commit, getters, dispatch }, page = 1) {
            commit('setPagPage', page);

            await messagesAPI.allMessages(
                getters.pageSize,
                page,
                axiosRes => {
                    commit('setMessages', axiosRes.data.data);

                    if (getters.totalPagesCount === null) {
                        commit('setTotalPagesCount', axiosRes.data.last_page);
                    }
                },
                axiosError => {
                    console.log('Fetch messages error; ' + axiosError)
                    dispatch('app/errorPage', null, { root: true });
                }
            );
        },
    },
};
