import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Удаление письма по id
     *
     * @param id
     * @param thenHandler
     * @param catchHandler
     */
    async deleteMessage(id, thenHandler = null, catchHandler = null) {
        await axios.delete('/contact/message/' + id)
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
