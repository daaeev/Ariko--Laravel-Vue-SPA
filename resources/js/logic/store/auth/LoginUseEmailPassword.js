import authAxios from '../../api/Auth';
import router from '../../../router/Router';
import adminAxios from '../../../axios/axiosWithAuthToken';

export default {
    actions: {
        /**
         * Логин пользователя
         *
         * @param commit
         * @param formData
         */
        async login({commit}, formData) {
            await authAxios.login(
                formData,
                async axiosRes => {
                    const token = axiosRes.data;

                    if (formData.get('save')) {
                        localStorage.setItem('token', token);
                    }

                    await commit('setToken', token);
                    adminAxios.defaults.headers.common['Authorization'] = token;
                    
                    router.push({name: 'admin.index'});
                },
                axiosError => {
                    throw axiosError;
                },
            )
        }
    },
};
