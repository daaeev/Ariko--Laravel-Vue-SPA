import CreateContactMessage from "../logic/api/ContactMessage";

export default {
    actions: {

        /**
         * Создание сообщения
         *
         * @param dispatch
         * @param formData
         * @returns {Promise<void>}
         */
        async sendMessage({dispatch}, formData) {
            await CreateContactMessage.saveMessage(
                formData,
                () => {},
                axiosError => {
                    throw axiosError;
                }
            );
        }
    },

    namespaced: true,
};
