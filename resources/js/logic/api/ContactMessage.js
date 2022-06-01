import axios from "../../axios/axios";

export default {

    /**
     * Создание сообщения
     *
     * @param formData
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async saveMessage(formData, thenHandler = null, catchHandler = null) {
        await axios.post("/message", formData)
            .then(thenHandler)
            .catch(catchHandler);
    },

};
