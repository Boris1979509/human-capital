import Axios from 'axios';

const state = {
  data: [],
  avatar: null,
};

const getters = {
  GET_EMPLOYER_DATA: state => state.data,
  GET_EMPLOYER_AVATAR: state => state.avatar,
};
const mutations = {
  SET_EMPLOYER_DATA: (state, payload) => {
    state.data = payload.data;
    state.avatar = payload.data?.avatar?.url ||
        require('@/assets/svg/user.svg');
  },
  SET_EMPLOYER_AVATAR: (state, payload) => {
    state.avatar = payload;
  },
};
const actions = {
  GET_EMPLOYER_DATA_FROM_SERVER: async ({commit}) => {
    let {data} = await Axios.get(
        `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer`);
    commit('SET_EMPLOYER_DATA', data);
  },
};

export default {
  state,
  getters,
  mutations,
  actions,
};
