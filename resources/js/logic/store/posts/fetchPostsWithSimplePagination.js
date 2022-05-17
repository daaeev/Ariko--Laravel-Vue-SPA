import FetchPosts from "../../api/Posts";
import FetchComments from "../../api/Comments";
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

        addCommentToSingle(state, comment) {
            if (state.single.comments) {
                state.single.comments[state.single.comments.length] = comment;
            } else {
                state.single.comments = [];
                state.single.comments[0] = comment;
            }
        }
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

        /**
         * Несколько операций для получения поста с идентификатором post_id
         * 1) Загружена ли раннее запрашиваемая работа (getters.single)
         *
         * 2) Проверяется наличие работы в массиве загруженных работ (getters.posts)
         *
         * 3) Работа запрашивается у АПИ (FetchPosts.fetchPostById)
         *
         * @param getters
         * @param commit
         * @param dispatch
         * @param post_id
         */
        async fetchPostById({ getters, commit, dispatch }, post_id) {

            // Загружена ли раннее запрашиваемая работа (getters.single)
            if (post_id == getters['single'].id) {
                return;
            }

            // Проверяется наличие работы в массиве загруженных работ (getters.posts)
            const post = getters['singleFromPostsState'](post_id);

            if (post) {
                commit('setSingle', post);

                FetchComments.fetchCommentsByPostId(
                    post_id,
                    axiosRes => {
                        for (const comment of axiosRes.data) {
                            commit('addCommentToSingle', comment);
                        }
                    },
                    axiosError => {
                        console.log('Fetch post comments error; ' + axiosError)
                        dispatch('app/errorPage', null, { root: true });
                    }
                )

                return;
            }

            // Работа запрашивается у АПИ (FetchPosts.fetchPostById)
            await FetchPosts.fetchPostById(
                post_id,
                async axiosRes => {
                    const post = axiosRes.data;
                    commit('setSingle', post);
                },
                axiosError => {
                    console.log('Fetch post by id error; ' + axiosError)
                    dispatch('app/errorPage', null, { root: true });
                }
            );
        },

        /**
         * Создание комментария
         *
         * @param commit
         * @param name
         * @param email
         * @param comment
         * @param post_id
         */
        async createComment({commit}, {name, email, comment, post_id}) {
            await FetchComments.createComment(
                name,
                email,
                comment,
                post_id,
                axiosRes => commit('addCommentToSingle', axiosRes.data),
                axiosError => {
                    throw axiosError;
                },
            );
        },
    },
}
