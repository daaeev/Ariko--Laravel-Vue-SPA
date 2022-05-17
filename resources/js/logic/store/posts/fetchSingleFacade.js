import FetchPosts from "../../api/Posts";
import FetchComments from '../../api/Comments';

export default {
    getters: {
        singleFromPostsState(state) {
            return post_id => state.posts.find((p) => p.id == post_id);
        },
    },

    mutations: {
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
    }
}