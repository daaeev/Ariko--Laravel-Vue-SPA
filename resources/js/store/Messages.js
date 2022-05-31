import fetchMessagesWithNumericPag from '../logic/store/messages/fetchMessagesWithNumericPag';

export default {
    state: {
        messages: [],

        ...fetchMessagesWithNumericPag.state,
    },

    getters: {
        messages(state) {
            return state.messages
        },

        ...fetchMessagesWithNumericPag.getters,
    },

    mutations: {
        setMessages(state, value) {
            state.messages = value;
        },

        ...fetchMessagesWithNumericPag.mutations,
    },

    actions: {
        ...fetchMessagesWithNumericPag.actions,
    },

    namespaced: true
};