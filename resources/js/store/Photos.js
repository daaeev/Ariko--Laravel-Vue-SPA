import fetchPhotosWithDynamicPag from "../logic/store/photos/fetchPhotosWithDynamicPag";

export default {
    state: {
        works: [],
        single: {},

        ...fetchPhotosWithDynamicPag.state
    },

    getters: {
        works(state) {
            return state.works;
        },

        single(state) {
            return state.single;
        },

        singleFromWorksState(state) {
            return work_id => state.works.find((w) => w.id == work_id);
        },

        ...fetchPhotosWithDynamicPag.getters
    },

    mutations: {
        addWorks(state, newWorks) {
            state.works = [...state.works, ...newWorks];
        },

        setSingle(state, work) {
            state.single = work;
        },

        ...fetchPhotosWithDynamicPag.mutations
    },

    actions: {
        ...fetchPhotosWithDynamicPag.actions
    },

    namespaced: true,
};
