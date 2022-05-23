import authAxios from '../../api/Auth';
import router from '../../../router/Router';

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
                axiosRes => {
                    const token = axiosRes.data;

                    if (formData.get('save')) {
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
