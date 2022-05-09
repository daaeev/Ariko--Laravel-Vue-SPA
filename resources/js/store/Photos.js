import FetchPhotosWorks from "../logic/FetchPhotosWorks";

export default {
    state: {
        works: [],
        single: {},
        isWorksLoading: false,
        fetch: FetchPhotosWorks,
    },

    getters: {
        isWorksLoading(state) {
            return state.isWorksLoading;
        },

        works(state) {
            return state.works;
        },

        single(state) {
            return state.single;
        },

        singleFromWorksState(state) {
            return work_id => state.works.find((w) => w.id == work_id);
        },

        fetch(state) {
            return state.fetch;
        }
    },

    mutations: {
        setIsWorksLoading(state, value) {
            state.isWorksLoading = value;
        },

        addWorks(state, newWorks) {
            state.works = [...state.works, ...newWorks];
        },

        setSingle(state, work) {
            state.single = work;
        },

        setImagesToSingle(state, images) {
            state.single.images = images;
        },
    },

    namespaced: true,
};
