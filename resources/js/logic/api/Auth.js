import axios from "axios";
import SiteSettings from '../../SiteSettings';

export default {
    /**
     * Аутентификация пользователя
     *
     * @param email
     * @param password
     * @param thenHandler
     * @param catchHandler
     */
    async login(email, password, thenHandler = null, catchHandler = null) {
        await axios.post(
            SiteSettings.api_domain + '/api/auth/login', 
            {
                email,
                password
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    },

    /**
     * Отправка запроса на проверку токена аутентификации
     * 
     * @param token 
     * @param thenHandler 
     * @param catchHandler 
     */
    async checkAuth(token, thenHandler = null, catchHandler = null) {
        await axios.post(
            SiteSettings.api_domain + '/api/auth/check',
            {
                token
            }
        )
            .then(thenHandler)
            .catch(catchHandler);
    }
}
