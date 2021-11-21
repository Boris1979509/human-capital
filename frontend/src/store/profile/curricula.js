import Axios from 'axios';

const state = {
    programs: [],
    program: [],
};

const getters = {
    GET_PROGRAMS_LIST: state => state.programs,
    GET_PROGRAM: state => state.program,
};
const mutations = {
    SET_PROGRAMS_LIST: (state, payload) => {
        state.programs = payload;
    },
    SET_PROGRAM: (state, payload) => {
        state.program = payload;
    },
};
const actions = {
    GET_PROGRAMS_LIST_FROM_SERVER: async (context, payload) => {
        if (payload.clear) state.programs = [];
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${payload.params.institution}/curricula`);
        context.commit('SET_PROGRAMS_LIST', data.data);
    },
    GET_PROGRAM_FROM_SERVER: async (context, payload) => {
        if (payload.clear) state.program = [];
        let { data } = await Axios.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${payload.params.institution}/curricula/${payload.params.curriculum}`);
        context.commit('SET_PROGRAM', data);
    },
};

export default {
    state,
    getters,
    mutations,
    actions,
};
