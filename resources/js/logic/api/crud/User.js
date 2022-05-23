import axios from "../../../axios/axiosWithAuthToken";


export default {
    /**
     * Отправка запроса на создание пользователя
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     */
    async createUser(formData, thenHandler = null, catchHandler = null) {
        await axios.post('/user', formData)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
