import Axios from 'axios';

const state = {
    data: [],
};

const getters = {
    GET_PERSONAL_DATA: state => state.data,
};
const mutations = {
    SET_PERSONAL_DATA: (state, payload) => {
        state.data = payload.data;
    },
};
const actions = {
    GET_PERSONAL_DATA_FROM_SERVER: async ({ commit }) => {
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user`);
        commit('SET_PERSONAL_DATA', data);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
