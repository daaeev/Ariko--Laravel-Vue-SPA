import Login from "../logic/store/auth/LoginUseEmailPassword";
import checkAuth from "../logic/store/auth/CheckAuthByTokenWithReq";

export default {
    state: {
        token: '',
    },

    getters: {
        token(state) {
            return state.token;
        }
    },

    mutations: {
        setToken(state, value) {
            state.token = value;
        }
    },

    actions: {
        ...Login.actions,
        ...checkAuth.actions,
    },

    namespaced: true,
};
