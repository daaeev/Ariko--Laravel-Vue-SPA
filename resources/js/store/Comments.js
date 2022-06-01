import fetchCommentsWithNumericPag from "../logic/store/comments/fetchCommentsWithNumericPag";
import deleteComment from "../logic/store/comments/deleteComment";
import setCheckedStatusToComment from "../logic/store/comments/setCheckedStatusToComment";

export default {
    state: {
        comments: [],

        ...fetchCommentsWithNumericPag.state
    },

    getters: {
        comments(state) {
            return state.comments;
        },

        ...fetchCommentsWithNumericPag.getters,
    },

    mutations: {
        setComments(state, value) {
            state.comments = value;
        },

        ...fetchCommentsWithNumericPag.mutations,
        ...deleteComment.mutations,
        ...setCheckedStatusToComment.mutations,
    },

    actions: {
        ...fetchCommentsWithNumericPag.actions,
        ...deleteComment.actions,
        ...setCheckedStatusToComment.actions,
    },

    namespaced: true,
};
