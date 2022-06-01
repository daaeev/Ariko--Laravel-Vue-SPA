import CommentsAPI from "../../api/admin/Comments";

export default {
    mutations: {
        setCheckedStatus(state, find_comment_id) {
            state.comments = state.comments.map(
                comment => ({
                    ...comment,
                    checked: comment.id === find_comment_id
                        ? true
                        : comment.checked
                })
            );
        }
    },

    actions: {
        /**
         * Изменение статуса комментария на 'Проверено'
         *
         * @param commit
         * @param comment_id
         * @returns {Promise<void>}
         */
        async setCheckedStatus({commit}, comment_id) {
            await CommentsAPI.setCheckedStatus(
                comment_id,
                () => commit('setCheckedStatus', comment_id),
                axiosError => {throw axiosError}
            );
        }
    },
};
