import router from '../router/Router.js';

export default {
    actions: {
        /**
         * Редирект на страницу с ошибкой
         */
        errorPage()
        {
            router.push({name: 'error'});
        }
    },

    namespaced: true,
};
