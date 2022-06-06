import fetchMessagesWithNumericPag from '../logic/store/messages/fetchMessagesWithNumericPag';
import deleteMessage from '../logic/store/messages/deleteMessage';
import createMessage from '../logic/store/messages/createMessage';

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
        ...deleteMessage.mutations,
    },

    actions: {
        ...fetchMessagesWithNumericPag.actions,
        ...deleteMessage.actions,
        ...createMessage.actions,
    },

    namespaced: true
};