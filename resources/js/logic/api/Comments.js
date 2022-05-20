import axios from "../../axios/axios";

export default {
    /**
     * Отправить запрос на создание комментария
     * 
     * @param name 
     * @param email 
     * @param comment 
     * @param post_id 
     * @param thenHandler 
     * @param catchHandler 
     */
     async createComment(name, email, comment, post_id, thenHandler = null, catchHandler = null) {
        await axios.post(
            '/comments',
            {
                name,
                email,
                comment,
                post_id
            }
        )
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