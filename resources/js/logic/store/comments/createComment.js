import FetchComments from '../../api/Comments';

export default {
    actions: {
        /**
         * Создание комментария
         *
         * @param commit
         * @param formData
         */
        async createComment({commit}, formData) {
            await FetchComments.createComment(
                formData,
                axiosRes => commit('addCommentToSingle', axiosRes.data),
                axiosError => {throw axiosError},
            );
        },
    }
};
