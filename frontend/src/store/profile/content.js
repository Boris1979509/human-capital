import Axios from 'axios';

const state = {
    data: [],
};

const getters = {
    GET_CONTENT_DATA: state => state.data,
};
const mutations = {
    SET_CONTENT_DATA: (state, payload) => {
        state.data = payload;
    },
};
const actions = {
    GET_CONTENT_DATA_FROM_SERVER: async ({ commit }) => {
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/content`);
        commit('SET_CONTENT_DATA', data);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
