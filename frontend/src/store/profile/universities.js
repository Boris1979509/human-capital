import Axios from 'axios';

const state = {
    data: [],
};

const getters = {
    GET_UNIVERSITIES_LIST: state => state.data,
};
const mutations = {
    SET_UNIVERSITIES_LIST: (state, payload) => {
        state.data = payload;
    },
};
const actions = {
    GET_UNIVERSITIES_LIST_FROM_SERVER: async ({ commit }) => {
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/universities`);
        commit('SET_UNIVERSITIES_LIST', data);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
