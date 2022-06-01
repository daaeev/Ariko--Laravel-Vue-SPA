import commentsApi from '../../api/admin/Comments';

export default {
    mutations: {
        deleteComment(state, find_comment_id) {
            state.comments = state.comments.filter((comment) => comment.id !== find_comment_id);
        }
    },

    actions: {
        /**
         * Удаление комментария
         *
         * @param commit
         * @param comment_id
         * @returns {Promise<void>}
         */
        async deleteComment({commit}, comment_id)
        {
            await commentsApi.deleteComment(
                comment_id,
                () => commit('deleteComment', comment_id),
                axiosError => {throw axiosError}
            );
        }
    },
};
