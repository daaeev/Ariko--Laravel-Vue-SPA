import authAxios from '../../api/Auth';
import router from '../../../router/Router';

export default {
    actions: {
        /**
         * Логин пользователя
         * 
         * @param commit 
         * @param authData 
         */
        async login({commit}, authData) {
            await authAxios.login(
                authData.email,
                authData.password,
                axiosRes => {
                    const token = axiosRes.data;

                    if (authData.save) {
                        localStorage.setItem('token', token);
                    }

                    commit('setToken', token);
                    router.push({name: 'admin.index'});
                },
                axiosError => {
                    throw axiosError;
                },
            )
        }
    },
};
