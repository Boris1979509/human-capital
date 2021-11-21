import Axios from 'axios';

const state = {
    data: [],
    professions: [],
};

const getters = {
    GET_DICTIONARIES_LIST: state => state.data,
    GET_PROFESSIONS_LIST: state => state.professions,
};
const mutations = {
    SET_DICTIONARIES_LIST: (state, payload) => {
        state.data = payload.data;
    },
    SET_PROFESSIONS_LIST: (state, payload) => {
        state.professions = payload.data;
    },
};
const actions = {
    GET_DICTIONARIES_LIST_FROM_SERVER: async ({ commit }) => {
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/dictionaries`);
        commit('SET_DICTIONARIES_LIST', data);
    },
    GET_PROFESSIONS_LIST_FROM_SERVER: async ({ commit }) => {
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/professions`);
        commit('SET_PROFESSIONS_LIST', data);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
