import fetchSingleWithWorkPagination from "../logic/store/videos/fetchSingleWithWorkPagination";
import fetchVideosWithDynamicPag from "../logic/store/videos/fetchVideosWithDynamicPag";

export default {
    state: {
        works: [],
        single: {},

        ...fetchVideosWithDynamicPag.state,
        ...fetchSingleWithWorkPagination.state,
    },

    getters: {
        works(state) {
            return state.works;
        },

        single(state) {
            return state.single;
        },

        ...fetchVideosWithDynamicPag.getters,
        ...fetchSingleWithWorkPagination.getters,
    },

    mutations: {
        ...fetchVideosWithDynamicPag.mutations,
        ...fetchSingleWithWorkPagination.mutations,
    },

    actions: {
        ...fetchVideosWithDynamicPag.actions,
        ...fetchSingleWithWorkPagination.actions,
    },
    
    namespaced: true,
}