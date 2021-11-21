import Axios from 'axios';

const apiHost = process.env.VUE_APP_DEFAULT_DEVELOP_HOST;

const state = {
  journal: [],
  inst_types: [],
  selections: [],
  events: [],
  filtered_programs: [],
  filtered_programs_meta: [],
  max_cost: null,
};

const getters = {
  GET_JOURNAL_PART: state => state.journal,
  GET_INST_TYPES: state => state.inst_types,
  GET_SELECTIONS_LIST: state => state.selections,
  GET_EVENTS_LIST: state => state.events,
  GET_FILTERED_PROGRAMS_LIST: state => state.filtered_programs,
  GET_FILTERED_PROGRAMS_LIST_META: state => state.filtered_programs_meta,
  GET_MAX_PROGRAM_COST: state => state.max_cost,
};
const mutations = {
  SET_JOURNAL_PART: (state, payload) => {
    state.journal = payload.data;
  },

  SET_INST_TYPES: (state, payload) => {
    state.inst_types = payload.data;
  },

  SET_SELECTIONS_LIST: (state, payload) => {
    state.selections = payload.data;
  },

  SET_EVENTS_PART: (state, payload) => {
    state.events = payload.data;
  },

  SET_FILTERED_PROGRAMS_LIST: (state, payload) => {
    const newArr = payload;
    state.filtered_programs = [...state.filtered_programs, ...newArr.data];
    state.filtered_programs_meta = newArr.meta;
  },

  SET_MAX_PROGRAM_COST: (state, payload) => {
    if(payload) {
      state.max_cost = payload;
    } else {
      state.max_cost = {MAX_PRICE: 1000000}
    }
  },
};
const actions = {
  GET_JOURNAL_PART_FROM_SERVER: async (context, payload) => {
    if (payload.clear) state.journal = [];

    let request = '?';
    for (let key in payload.params) {
      if (payload.params[key]) {
        request += `&${key}=${payload.params[key]}`;
      }
    }

    let {data} = await Axios.get(`${apiHost}api/journal${request}`);
    context.commit('SET_JOURNAL_PART', data);
  },

  GET_EVENTS_PART_FROM_SERVER: async (context, payload) => {
    if (payload.clear) state.events = [];

    let request = '?';
    for (let key in payload.params) {
      if (payload.params[key]) {
        request += `&${key}=${payload.params[key]}`;
      }
    }

    let {data} = await Axios.get(`${apiHost}api/journal${request}`);
    context.commit('SET_EVENTS_PART', data);
  },

  GET_INST_TYPES_FROM_SERVER: async (context, payload) => {
    if (payload.clear) state.inst_types = [];

    let request = '?';
    for (let key in payload.params) {
      if (payload.params[key]) {
        request += `&${key}=${payload.params[key]}`;
      }
    }

    let {data} = await Axios.get(`${apiHost}api/dictionaries${request}`);
    context.commit('SET_INST_TYPES', data);
  },

  GET_SELECTIONS_LIST_FROM_SERVER: async ({commit}) => {
    let {data} = await Axios.get(`${apiHost}api/selections/?filter=top&limit=6`);
    commit('SET_SELECTIONS_LIST', data);
  },

  GET_FILTERED_PROGRAMS_LIST_FROM_SERVER: async (context, payload) => {
    if (payload.clear) state.filtered_programs = [];

    let request = '?';
    for (let key in payload.params) {
      if (payload.params[key]) {
        request += `&${key}=${payload.params[key]}`;
      }
    }

    let {data} = await Axios.get(`${apiHost}api/curricula${request}`);
    context.commit('SET_FILTERED_PROGRAMS_LIST', data);
  },

  GET_MAX_PROGRAM_COST_FROM_SERVER: async (context, payload) => {
    if (payload.clear) state.max_cost = null;

    let request = '?';
    for (let key in payload.params) {
      if (payload.params[key]) {
        request += `&${key}=${payload.params[key]}`;
      }
    }

    let {data} = await Axios.get(`${apiHost}api/curricula/summary${request}`);
    context.commit('SET_MAX_PROGRAM_COST', data);
  }
};

export default {
  state,
  getters,
  mutations,
  actions,
};
