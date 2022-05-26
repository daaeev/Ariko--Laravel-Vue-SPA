import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Отправка запроса на создание поста
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     */
    async createPost(formData, thenHandler = null, catchHandler = null) {
        await axios.post('/post', formData)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
