import Vue from 'vue';
import Vuex from 'vuex';

import user from '@/store/user';
import personal from '@/store/profile/personal';
import cities from '@/store/profile/cities';
import universities from '@/store/profile/universities';
import dictionaries from '@/store/profile/dictionaries';
import content from '@/store/profile/content';
import organization from '@/store/profile/organization';
import cirricula from '@/store/profile/curricula';
import region from '@/store/region';
import main from '@/store/main';
import employer from '@/store/profile/employer';

Vue.use(Vuex);

export default new Vuex.Store({
    state: {},
    mutations: {},
    actions: {},
    modules: {
        user,
        personal,
        cities,
        universities,
        dictionaries,
        content,
        organization,
        cirricula,
        region,
        main,
        employer,
    }
});

