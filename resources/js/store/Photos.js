import fetchPhotosWithDynamicPag from "../logic/store/photos/fetchPhotosWithDynamicPag";
import fetchSingleWithWorkPagination from "../logic/store/photos/fetchSingleWithWorkPagination";

export default {
    state: {
        works: [],
        single: {},

        ...fetchPhotosWithDynamicPag.state,
        ...fetchSingleWithWorkPagination.state,
    },

    getters: {
        works(state) {
            return state.works;
        },

        single(state) {
            return state.single;
        },

        ...fetchPhotosWithDynamicPag.getters,
        ...fetchSingleWithWorkPagination.getters,
    },

    mutations: {
        ...fetchPhotosWithDynamicPag.mutations,
        ...fetchSingleWithWorkPagination.mutations,
    },

    actions: {
        ...fetchPhotosWithDynamicPag.actions,
        ...fetchSingleWithWorkPagination.actions,
    },

    namespaced: true,
};
