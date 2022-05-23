import axios from "../../axios/axios";

export default {
    /**
     * Отправить запрос на создание комментария
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     */
     async createComment(formData, thenHandler = null, catchHandler = null) {
        await axios.post('/comments', formData)
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Получить комментарии поста с id = post_id
     *
     * @param post_id
     * @param thenHandler
     * @param catchHandler
     */
    async fetchCommentsByPostId(post_id, thenHandler = null, catchHandler = null) {
        await axios.get('/comments/' + post_id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
