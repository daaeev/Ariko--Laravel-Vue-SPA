import axios from "axios";
import SiteSettings from '../../SiteSettings';

export default {
    /**
     * Получение работ (фотографий)
     *
     * @param limit
     * @param page
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async fetchWorks(limit, page,thenHandler = null, catchHandler = null) {
        await axios.get(
            SiteSettings.api_domain + "/api/works/photos",
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
     * Получить работу с идентификатором work_id
     *
     * @param work_id
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async fetchWorkById(work_id, thenHandler = null, catchHandler = null) {
        await axios.get(
            SiteSettings.api_domain + `/api/works/photos/${work_id}`
        )
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Получить идентификаторы 'следующей' и 'предыдущей' работы
     *
     * @param work_id
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async fetchNextPrevIds(work_id, thenHandler = null, catchHandler = null) {
        await axios.get(
            SiteSettings.api_domain + `/api/works/photos/next/prev/${work_id}`
        )
            .then(thenHandler)
            .catch(catchHandler);
    }
};
