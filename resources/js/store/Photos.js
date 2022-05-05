import axios from "axios";

export default {
    state: {
        works: [],
        pageSize: 6,
        totalPagesCount: null,
        pagPage: null,
        isWorksLoading: false,
    },

    getters: {
        isWorksLoading(state) {
            return state.isWorksLoading;
        },

        pagPage(state) {
            return state.pagPage;
        },

        totalPagesCount(state) {
            return state.totalPagesCount;
        },

        works(state) {
            return state.works;
        }
    },

    mutations: {
        setIsWorksLoading(state, value) {
            state.isWorksLoading = value;
        },

        addWorks(state, newWorks) {
            state.works = [...state.works, ...newWorks];
        },

        setTotalPagesCount(state, value) {
            state.totalPagesCount = value;
        },

        incrementPagPage(state) {
            if (state.pagPage === null) {
                state.pagPage = 1;
            } else {
                state.pagPage++;
            }
        }
    },

    actions: {
        async fetchWorks({commit, state}) {
            commit('setIsWorksLoading', true);

            const res = await axios.get(
                "http://ariko.lrvl/api/works/photos",
                {
                    params: {
                        _limit: state.pageSize,
                        page: state.pagPage ?? 1,
                    },
                }
            );

            if (state.totalPagesCount === null) {
                commit('setTotalPagesCount', res.data.last_page);
            }
            commit('addWorks', res.data.data);
            commit('incrementPagPage');
            commit('setIsWorksLoading', false);
        }
    },

    namespaced: true,
};
