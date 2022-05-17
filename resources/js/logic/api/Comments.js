import SiteSettings from "../../SiteSettings";

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
            SiteSettings.api_domain + '/api/comments',
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
        await axios.get(SiteSettings.api_domain + '/api/comments/' + post_id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};