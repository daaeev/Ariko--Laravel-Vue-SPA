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
    },

    /**
     * Отправка запроса на удаление поста
     * 
     * @param post_id 
     * @param thenHandler 
     * @param catchHandler 
     */
    async deletePost(
        post_id,
        thenHandler = null,
        catchHandler = null
    ) {
        await axios.delete('/post/' + post_id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
