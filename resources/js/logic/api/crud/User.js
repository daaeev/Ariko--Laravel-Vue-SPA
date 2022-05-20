import axios from "../../../axios/axiosWithAuthToken";


export default {
    /**
     * Отправка запроса на создание пользователя
     *
     * @param email
     * @param password
     * @param thenHandler
     * @param catchHandler
     */
    async createUser(email, password, thenHandler = null, catchHandler = null) {
        await axios.post(
            '/user',
            {
                email,
                password
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    }
};
