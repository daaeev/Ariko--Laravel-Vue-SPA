import fetchPostsWithSimplePagination from "../logic/store/posts/fetchPostsWithSimplePagination";
import fetchSingleFacade from "../logic/store/posts/fetchSingleFacade";
import createComment from '../logic/store/posts/createComment';

export default {
    state: {
        posts: [],
        single: {},

        ...fetchPostsWithSimplePagination.state
    },

    getters: {
        posts(state) {
            return state.posts;
        },

        single(state) {
            return state.single;
        },

        ...fetchPostsWithSimplePagination.getters,
        ...fetchSingleFacade.getters,
    },

    mutations: {
        setPosts(state, posts) {
            state.posts = posts;
        },

        setSingle(state, post) {
            state.single = post;
        },

        ...fetchPostsWithSimplePagination.mutations,
        ...fetchSingleFacade.mutations,
    },

    actions: {
        ...fetchPostsWithSimplePagination.actions,
        ...fetchSingleFacade.actions,
        ...createComment.actions,
    },

    namespaced: true,
};
