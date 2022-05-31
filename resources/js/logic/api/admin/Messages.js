import axios from "../../../axios/axiosWithAuthToken";

export default {
    /**
     * Удаление письма по id
     * 
     * @param id 
     * @param thenHandler 
     * @param catchHandler 
     */
    deleteMessage(id, thenHandler = null, catchHandler = null) {
        axios.delete('/contact/message/' + id)
            .then(thenHandler)
            .catch(catchHandler);
    }
};