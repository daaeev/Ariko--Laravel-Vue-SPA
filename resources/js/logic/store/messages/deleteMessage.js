import messAPI from '../../api/admin/Messages';

export default {
    mutations: {
        deleteMessage(state, find_id) {
            state.messages = state.messages.filter((message) => message.id !== find_id);
        }
    },

    actions: {
        /**
         * Удаление письма
         * 
         * @param commit 
         * @param message_id 
         */
        async deleteMessage({commit}, message_id)
        {
            await messAPI.deleteMessage(
                message_id,
                () => commit('deleteMessage', message_id),
                axiosError => {throw axiosError}
            );
        }
    },
};
