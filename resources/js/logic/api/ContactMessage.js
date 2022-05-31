import axios from "../../axios/axios";

export default {

    /**
     * Создание сообщения
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async saveMessage(formData, thenHandler = null, catchHandler = null) {
        await axios.post("/message", formData)
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Получение всех писем
     * 
     * @param limit 
     * @param page 
     * @param thenHandler 
     * @param catchHandler 
     */
    async allMessages(limit, page = 1, thenHandler = null, catchHandler = null) {
        await axios.get(
            "/contact/messages",
            {
                params: {
                    _limit: limit,
                    page: page,
                },
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    }
};
