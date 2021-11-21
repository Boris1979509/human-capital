import Axios from 'axios';

const state = {
    data: [],
    avatar: null,
};

const getters = {
    GET_ORG_DATA: state => state.data,
    GET_ORG_AVATAR: state => state.avatar,
};
const mutations = {
    SET_ORG_DATA: (state, payload) => {
        state.data = payload.data;
        state.avatar = payload.data[0]?.avatar?.url || require('@/assets/svg/user.svg');
    },
    SET_ORG_AVATAR: (state, payload) => {
        state.avatar = payload;
    },
};
const actions = {
    GET_ORG_DATA_FROM_SERVER: async ({ commit }) => {
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions`);
        commit('SET_ORG_DATA', data);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
