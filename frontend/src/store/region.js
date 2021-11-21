import Axios from 'axios';

const state = {
  data: [],
  name: '',
};

const getters = {
  GET_REGION_DATA: state => state.data,
  GET_REGION_NAME: state => state.name,
};
const mutations = {
  SET_REGION_DATA: (state, payload) => {
    state.data = payload;
  },
  SET_REGION_NAME: (state, payload) =>  {
    state.name = payload.name;
  }
};
const actions = {
  GET_REGION_NAME_FROM_SERVER: async ({ commit }) => {
    let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/region`);
    commit('SET_REGION_NAME', data);
  },
  GET_REGION_DATA_FROM_SERVER: async ({ commit }) => {
    let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/region/summary`);
    commit('SET_REGION_DATA', data);
  },
};

export default {
  state,
  getters,
  mutations,
  actions,
};
