import axios from "axios";
import SiteSettings from '../../SiteSettings';

export default {
    /**
     * Получить все посты
     * 
     * @param limit 
     * @param page 
     * @param thenHandler 
     * @param catchHandler 
     */
    async allPosts(limit, page, thenHandler = null, catchHandler = null) {
        await axios.get(
            SiteSettings.api_domain + "/api/posts",
            {
                params: {
                    _limit: limit,
                    page: page ?? 1,
                },
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Получить все посты с тегом tag
     * 
     * @param limit 
     * @param page 
     * @param tag
     * @param thenHandler 
     * @param catchHandler 
     */
     async allPostsByTag(limit, page, tag, thenHandler = null, catchHandler = null) {
        await axios.get(
            SiteSettings.api_domain + "/api/posts/tag/" + tag,
            {
                params: {
                    _limit: limit,
                    page: page ?? 1,
                },
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Получить пост по идентификатору
     * 
     * @param post_id
     * @param thenHandler 
     * @param catchHandler 
     */
    async fetchPostById(post_id, thenHandler = null, catchHandler = null) {
        await axios.get(SiteSettings.api_domain + "/api/posts/" + post_id)
            .then(thenHandler)
            .catch(catchHandler);
    },
};