import FetchPosts from "../../api/Posts";
import SiteSettings from "../../../SiteSettings";

export default {
    state: {
        isPostsLoading: false,
        pagPage: null,
        totalPagesCount: null,
        pageSize: SiteSettings.blogPerPage,
    },

    getters: {
        pagPage(state) {
            return state.pagPage;
        },

        pageSize(state) {
            return state.pageSize;
        },

        isPostsLoading(state) {
            return state.isPostsLoading;
        },

        totalPagesCount(state) {
            return state.totalPagesCount;
        },
    },

    mutations: {
        setIsPostsLoading(state, value) {
            state.isPostsLoading = value;
        },

        setTotalPagesCount(state, value) {
            state.totalPagesCount = value;
        },

        setPagPage(state, page) {
            state.pagPage = page;
        },
    },

    actions: {
        /**
         * Загрузить посты при помощи АПИ
         *
         * @param commit
         * @param getters
         * @param dispatch
         * @param page
         * @returns {Promise<void>}
         */
        async fetchPosts({ commit, getters, dispatch }, page = 1) {
            commit('setIsPostsLoading', true);
            commit('setPagPage', page);

            await FetchPosts.allPosts(
                getters.pageSize,
                page,
                axiosRes => {
                    commit('setPosts', axiosRes.data.data);

                    if (getters.totalPagesCount === null) {
                        commit('setTotalPagesCount', axiosRes.data.last_page);
                    }
                },
                axiosError => {
                    console.log('Fetch posts error; ' + axiosError)
                    dispatch('app/errorPage', null, { root: true });
                }
            );

            commit('setIsPostsLoading', false);
        },

        /**
         * Загрузить посты при помощи АПИ
         *
         * @param commit
         * @param getters
         * @param dispatch
         * @param page
         * @param tag
         * @returns {Promise<void>}
         */
        async fetchPostsByTag({ commit, getters, dispatch }, {page = 1, tag}) {
            commit('setIsPostsLoading', true);
            commit('setPagPage', page);

            await FetchPosts.allPostsByTag(
                getters.pageSize,
                page,
                tag,
                axiosRes => {
                    commit('setPosts', axiosRes.data.data);

                    if (getters.totalPagesCount === null) {
                        commit('setTotalPagesCount', axiosRes.data.last_page);
                    }
                },
                axiosError => {
                    console.log('Fetch posts error; ' + axiosError)
                    dispatch('app/errorPage', null, { root: true });
                }
            );

            commit('setIsPostsLoading', false);
        },
    },
}
