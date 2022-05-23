import axios from "../../axios/axios";

export default {
    /**
     * Аутентификация пользователя
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     */
    async login(formData, thenHandler = null, catchHandler = null) {
        await axios.post('/auth/login', formData)
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Отправка запроса на проверку токена аутентификации
     *
     * @param token
     * @param thenHandler
     * @param catchHandler
     */
    async checkAuth(token, thenHandler = null, catchHandler = null) {
        await axios.post(
            '/auth/check',
            {
                token
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    }
}
