import Axios from 'axios';

const state = {
    data: [],
};

const getters = {
    GET_CITIES_LIST: state => state.data,
};
const mutations = {
    SET_CITIES_LIST: (state, payload) => {
        state.data = payload;
    },
};
const actions = {
    GET_CITIES_LIST_FROM_SERVER: async ({ commit }) => {
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/cities`);
        commit('SET_CITIES_LIST', data);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
