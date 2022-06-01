import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Получение всех комментариев
     *
     * @param limit
     * @param page
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async fetchComments(
        limit,
        page = 1,
        thenHandler = null,
        catchHandler = null,
    ) {
        await axios.get(
            '/comments',
            {
                params: {
                    _limit: limit,
                    page: page,
                },
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Запрос на удаление комментария
     *
     * @param id
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async deleteComment(id, thenHandler = null, catchHandler = null) {
        await axios.delete('/comments/' + id)
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Запрос на изменение статуса комментария на статус 'Проверено'
     *
     * @param id
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async setCheckedStatus(id, thenHandler = null, catchHandler = null) {
        await axios.patch('/comments/checked/' + id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
