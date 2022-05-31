import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Запрос на получение всех тегов
     * 
     * @param thenHandler 
     * @param catchHandler 
     */
    async fetchTags(thenHandler = null, catchHandler = null) {
        await axios.get('/tags')
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Запрос на добавление тега посту
     * 
     * @param formData 
     * @param thenHandler 
     * @param catchHandler 
     */
    async addTagToPost(formData, thenHandler = null, catchHandler = null) {
        await axios.post('/add/tag/to/post', formData)
            .then(thenHandler)
            .catch(catchHandler);
    }
};