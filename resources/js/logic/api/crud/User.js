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
    },

    /**
     * Отправка запроса на удаление пользователя
     * 
     * @param user_id 
     * @param thenHandler 
     * @param catchHandler 
     */
    async deleteUser(
        user_id,
        thenHandler = null,
        catchHandler = null
    ) {
        await axios.delete('/user/' + user_id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
