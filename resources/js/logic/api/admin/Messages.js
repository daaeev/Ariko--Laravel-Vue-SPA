import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Удаление письма по id
     * 
     * @param id 
     * @param thenHandler 
     * @param catchHandler 
     */
    async deleteMessage(id, thenHandler = null, catchHandler = null) {
        await axios.delete('/contact/message/' + id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};