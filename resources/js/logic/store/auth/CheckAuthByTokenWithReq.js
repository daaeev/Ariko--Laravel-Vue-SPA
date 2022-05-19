import authAxios from '../../api/Auth';

export default {
    actions: {
        /**
         * Проверка токена аутентификации
         * 
         * @param getters
         * @param commit
         */
        async checkAuth({getters, commit}) {
            const token = getters.token
                ? getters.token
                : localStorage.getItem('token');

            if (!token) {
                return false
            }

            let auth = false;

            await authAxios.checkAuth(
                token,
                axiosRes => auth = true,
                axiosError => {
                    commit('setToken', '');
                    localStorage.removeItem('token');
                }
            );

            return auth;
        },
    },
};