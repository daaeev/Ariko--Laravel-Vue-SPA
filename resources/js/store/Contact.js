import CreateContactMessage from "../logic/CreateContactMessage";

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
            let responseError = null

            await CreateContactMessage.saveMessage(
                name,
                email,
                message,
                axiosRes => {},
                axiosError => {
                    responseError = axiosError;
                }
            );

            if (responseError) {
                throw responseError;
            }
        }
    },

    namespaced: true,
};
