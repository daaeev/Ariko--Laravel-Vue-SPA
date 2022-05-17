import fetchPostsWithSimplePagination from "../logic/store/posts/fetchPostsWithSimplePagination";

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

        singleFromPostsState(state) {
            return post_id => state.posts.find((p) => p.id == post_id);
        },

        ...fetchPostsWithSimplePagination.getters
    },

    mutations: {
        setPosts(state, posts) {
            state.posts = posts;
        },

        setSingle(state, post) {
            state.single = post;
        },

        ...fetchPostsWithSimplePagination.mutations
    },

    actions: {
        ...fetchPostsWithSimplePagination.actions
    },

    namespaced: true,
};
