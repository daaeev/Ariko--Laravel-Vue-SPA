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
    }
};
