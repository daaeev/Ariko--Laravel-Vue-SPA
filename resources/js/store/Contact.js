import CreateContactMessage from "../logic/api/ContactMessage";

export default {
    actions: {

        /**
         * Создание сообщения
         *
         * @param dispatch
         * @param name
         * @param email
         * @param message
         * @returns {Promise<void>}
         */
        async sendMessage({dispatch}, {name, email, message}) {
            await CreateContactMessage.saveMessage(
                name,
                email,
                message,
                axiosRes => {},
                axiosError => {
                    throw axiosError;
                }
            );
        }
    },

    namespaced: true,
};
