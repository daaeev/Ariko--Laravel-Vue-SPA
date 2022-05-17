import FetchComments from '../../api/Comments';

export default {
    actions: {
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
    }
};