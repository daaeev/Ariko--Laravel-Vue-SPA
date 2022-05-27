import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Отправка запроса на создание работы (видео)
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async createVideoWork(
        formData,
        thenHandler = null,
        catchHandler = null
    ) {
        await axios.post('/works/video', formData)
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Отправка запроса на удаление работы (видео)
     * 
     * @param work_id 
     * @param thenHandler 
     * @param catchHandler 
     */
    async deleteWork(
        work_id,
        thenHandler = null,
        catchHandler = null
    ) {
        await axios.delete('/works/videos/' + work_id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
