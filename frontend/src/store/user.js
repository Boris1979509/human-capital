import Axios from 'axios';

const state = {
    status: '',
    api_token: localStorage.getItem('api_token') || '',
    user: {},
    avatar: null,
    progress: 0,
    isLoading: false,
};

const getters = {
    GET_USER: state => state.user,
    GET_USER_AVATAR: state => state.avatar,
    GET_LOGIN_STATUS: state => !!state.api_token,
    GET_LOGIN_LOADING: state => state.isLoading,
};
const mutations = {
    SET_TOKEN: (state, token) => {
        state.api_token = token;
    },

    SET_USER_AVATAR: (state, payload) => {
        state.avatar = payload || require('@/assets/svg/user.svg');
    },

    CHANGE_LOADING: (state, payload) => {
        state.isLoading = payload;
    },

    UNSET_TOKEN(state) {
        state.api_token = '';
    },

    SET_USER: (state, payload) => {
        state.user = {
            id: payload?.data?.id,
            type: payload?.data?.type,
            first_name: payload?.data?.personal?.first_name,
            last_name: payload?.data?.personal?.last_name,
            email: payload?.data?.email,
            progress: payload?.data?.progress || 0
        };
        state.avatar = payload?.data?.avatar?.url || require('@/assets/svg/user.svg');
    },

    UNSET_USER: (state) => {
        state.user = {
            id: null,
            type: null,
            first_name: null,
            last_name: null,
            email: null,
            progress: 0,
        };
        state.avatar = require('@/assets/svg/user.svg');
    },
};
const actions = {
    GET_USER_FROM_SERVER: async ({commit}) => {
        if (state.user && Object.keys(state.user).length === 0 && state.user.constructor === Object) {
            let {data} = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user`);
            commit('SET_USER', data);
        }
    },

    LOG_ME_IN_KEYKLOAK: ({commit}, token) =>{
        localStorage.setItem('api_token', token);
        Axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
        commit('SET_TOKEN', token);
    },

    LOG_ME_IN: ({commit}, user) => {
        return new Promise((resolve, reject) => {
            Axios.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/auth/login`, user)
              .then(response => {
                  const token = response.data.access_token;
                  localStorage.setItem('api_token', token);
                  Axios.defaults.headers.common['Authorization'] = `Bearer ${token}`;
                  commit('SET_TOKEN', token);
                  resolve(response);
              })
              .catch(err => {
                  localStorage.removeItem('api_token');
                  reject(err);
              })
        })
    },

    LOG_ME_OUT: ({commit}) => {
        return new Promise(resolve => {
            commit('UNSET_TOKEN');
            Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/auth/logout`)
              .then(() => {
                  commit('UNSET_USER');
                  localStorage.removeItem('api_token');
                  delete Axios.defaults.headers.common['Authorization'];
                  resolve();

                  // TODO: temporary reset
                  window.location.reload();
              })

        })
    }
};

export default {
    state,
    getters,
    mutations,
    actions,
};
