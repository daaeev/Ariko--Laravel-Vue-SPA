import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Отправка запроса на создание работы (фотографии)
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     */
    async createPhotoWork(
        formData,
        thenHandler = null,
        catchHandler = null
    ) {
        await axios.post('/works/photos', formData)
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Отправка запроса на добавление изображений к работе
     * 
     * @param formData
     * @param thenHandler
     * @param catchHandler
     */
    async addImagesToWork(
        formData,
        thenHandler = null,
        catchHandler = null
    ) {
        await axios.post('/works/photos/images', formData)
            .then(thenHandler)
            .catch(catchHandler);
    }
};
