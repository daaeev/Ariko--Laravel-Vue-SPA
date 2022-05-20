import axios from "../../axios/axios";

export default {
    /**
     * Аутентификация пользователя
     *
     * @param email
     * @param password
     * @param thenHandler
     * @param catchHandler
     */
    async login(email, password, thenHandler = null, catchHandler = null) {
        await axios.post(
            '/auth/login', 
            {
                email,
                password
            }
        )
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
