import axios from "axios";
import SiteSettings from '../../SiteSettings';

export default {

    /**
     * Создание сообщения
     *
     * @param name
     * @param email
     * @param message
     * @param thenHandler
     * @param catchHandler
     * @returns {Promise<void>}
     */
    async saveMessage(name, email, message,thenHandler = null, catchHandler = null) {
        await axios.post(
            SiteSettings.api_domain + "/api/message",
            {
                name,
                email,
                message
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    },

};
