import axios from "../../axios/axios";

export default {
    /**
     * Получение работ (видео)
     *
     * @param limit
     * @param page
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async fetchWorks(limit, page,thenHandler = null, catchHandler = null) {
        await axios.get(
            "/works/videos",
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
            `/works/videos/${work_id}`
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
            `/works/videos/next/prev/${work_id}`
        )
            .then(thenHandler)
            .catch(catchHandler);
    }
};
