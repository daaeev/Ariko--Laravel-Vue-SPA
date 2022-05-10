import axios from "axios";
import store from '../store';

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
    async fetchWorks(limit, page,thenHandler, catchHandler = null) {
        await axios.get(
            store.getters['app/api_domain'] + "/api/works/photos",
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
    async fetchWorkById(work_id, thenHandler, catchHandler = null) {
        await axios.get(
            store.getters['app/api_domain'] + `/api/works/photos/${work_id}`
        )
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Получение всех фотографий работы с идентификатором work_id
     *
     * @param work_id
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async fetchImagesByWorkId(work_id, thenHandler, catchHandler = null) {
        await axios.get(
            store.getters['app/api_domain'] + `/api/works/photos/images/${work_id}`
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
    async fetchNextPrevIds(work_id, thenHandler, catchHandler = null) {
        await axios.get(
            store.getters['app/api_domain'] + `/api/works/photos/next/prev/${work_id}`
        )
            .then(thenHandler)
            .catch(catchHandler);
    }
};
