import Vue from 'vue';
import App from './App.vue';
import Axios from 'axios';
import moment from 'moment';
import store from './store';
import router from './router';
import lineClamp from 'vue-line-clamp';
import {Datetime} from 'vue-datetime';
import DateFilter from './utils/Filters/DateFilter/';
import DateTimeFilter from './utils/Filters/DateTimeFilter/';
import Vuelidate from 'vuelidate';

// service
Vue.component('Sprite', () => import('./components/Sprite'));
Vue.component('Icon', () => import('./components/Icon'));
Vue.component('IconCustom', () => import('./components/IconCustom'));
Vue.component('CircleProgress', () => import('./components/CircleProgress'));
Vue.component('Loading', () => import('./components/Loading'));
Vue.component('NothingFound', () => import('./components/NothingFound'));
Vue.use(lineClamp, {});

// utils
Vue.filter('date', DateFilter);
Vue.filter('dateTime', DateTimeFilter);
Vue.component('Modal', () => import('./components/Modal'));
Vue.component('ModalGallery', () => import('./components/ModalGallery'));

// controls
Vue.component('Tabs', () => import('./components/Tabs'));
Vue.component('CardsFilter', () => import('./components/CardsFilter'));
Vue.component('JournalFilter', () => import('./components/JournalFilter'));

// profile forms
Vue.component('EducationForm', () => import('./components/UserProfile/EducationForm'));
Vue.component('PersonalInfoForm', () => import('./components/UserProfile/PersonalInfoForm'));
Vue.component('ProgramList', () => import('./components/OrgProfile/ProgramsList'));

// page parts
Vue.component('Logo', () => import('./components/Logo'));
Vue.component('Breadcrumbs', () => import('./components/Breadcrumbs'));
Vue.component('Header', () => import('./components/Header'));
Vue.component('Footer', () => import('./components/Footer'));
Vue.component('LoginStep', () => import('./components/LoginAuthComponents/LoginStep'));
Vue.component('RegistrationBlock', () => import('./components/LoginAuthComponents/RegistrationBlock'));
Vue.component('LoginBlock', () => import('./components/LoginAuthComponents/LoginBlock'));

// main view parts
Vue.component('PortalSections', () => import('./components/MainViewParts/PortalSections'));
Vue.component('EducationCatalog', () => import('./components/MainViewParts/EducationCatalog'));
Vue.component('ProfessionsCatalog', () => import('./components/MainViewParts/ProfessionsCatalog'));
Vue.component('Compilations', () => import('./components/MainViewParts/Compilations'));
Vue.component('JournalCatalog', () => import('./components/MainViewParts/JournalCatalog'));
Vue.component('Opinions', () => import('./components/MainViewParts/Opinions'));
Vue.component('EventsCatalog', () => import('./components/MainViewParts/EventsCatalog'));
Vue.component('WhereToGo', () => import('./components/MainViewParts/WhereToGo'));

// organizations parts
Vue.component('OrganizationsHeadSection', () => import('./components/OrganizationsParts/OrganizationsHeadSection'))
Vue.component('OrganizationsCounters', () => import('./components/OrganizationsParts/OrganizationsCounters'))
Vue.component('OrganizationsFilters', () => import('./components/OrganizationsParts/OrganizationsFilters'))

// cards
Vue.component('PriceCard', () => import('./components/Cards/PriceCard'));
Vue.component('NewsCard', () => import('./components/Cards/NewsCard'));
Vue.component('NewsCardSmall', () => import('./components/Cards/NewsCardSmall'));
Vue.component('CompilationCard', () => import('./components/Cards/CompilationCard'));
Vue.component('PersonCard', () => import('./components/Cards/PersonCard'));
Vue.component('PersonCardSmall', () => import('./components/Cards/PersonCardSmall'));
Vue.component('OrganizationCard', () => import('./components/Cards/OrganizationCard'));
Vue.component('EmployeeCard', () => import('./components/Cards/EmployeeCard'));

// inputs
Vue.component('Button', () => import('./components/InputComponents/Button'));
Vue.component('TextInput', () => import('./components/InputComponents/TextInput'));
Vue.component('Checkbox', () => import('./components/InputComponents/Checkbox'));
Vue.component('Select', () => import('./components/InputComponents/Select'));
Vue.component('Upload', () => import('./components/InputComponents/Upload'));
Vue.component('UploadWithCrop', () => import('./components/InputComponents/UploadWithCrop'));
Vue.component('TagsCloud', () => import('./components/InputComponents/TagsCloud'));
Vue.component('Radio', () => import('./components/InputComponents/Radio'));
Vue.component('Range', () => import('./components/InputComponents/Range'));
Vue.component('AutocompleteSearch', () => import('./components/InputComponents/AutocompleteSearch'));
Vue.component('RirDatePicker', () => import('./components/InputComponents/RirDatePicker'));
Vue.component('TextEditor', () => import('./components/InputComponents/TextEditor'));
Vue.component('InputAutoComplete', () => import('./components/InputComponents/InputAutoComplete'));
Vue.component('datetime', Datetime);

// widgets
Vue.component('Slider', () => import('./components/Slider'));
Vue.component('RelatedSlider', () => import('./components/RelatedSlider'));
Vue.component('PhotoSlider', () => import('./components/PhotoSlider'));
Vue.component('EmployeesSlider', () => import('./components/EmployeesSlider'));
Vue.component('SocialSharing', () => import('./components/SocialSharing'));

// views
Vue.component('MainView', () => import('./views/MainView'));
Vue.component('LoginAuthView', () => import('./views/LoginAuth/LoginAuthView'));
Vue.component('PasswordResetView', () => import('./views/LoginAuth/PasswordResetView'));
Vue.component('ProfileUserWrapper', () => import('./views/Profile/User/ProfileUserWrapper'));
Vue.component('ProfileOrgWrapper', () => import('./views/Profile/Organization/ProfileOrgWrapper'));
Vue.component('ProfileEmployerWrapper', () => import('./views/Profile/Employer/ProfileEmployerWrapper'));
Vue.component('LoginAuthWrapper', () => import('./views/LoginAuth/LoginAuthWrapper'));

//selections
Vue.component('institutionComponent', () => import('./components/Selections/institutionComponent'));
Vue.component('curriculumComponent', () => import('./components/Selections/curriculumComponent'));
Vue.component('newsComponent', () => import('./components/Selections/newsComponent'));
Vue.component('eventComponent', () => import('./components/Selections/eventComponent'));
Vue.component('articleComponent', () => import('./components/Selections/articleComponent'));

import computed from './computed';
import methods from './methods';
import http from './http';
import clickOutside from './directives/click-outside';

Vue.directive('click-outside', clickOutside);

moment.locale('ru');
Vue.prototype.$moment = moment;
Vue.prototype.$http = Axios;

const apiToken = localStorage.getItem('api_token');

if (apiToken) {
  Vue.prototype.$http.defaults.headers.common['Authorization'] = `Bearer ${apiToken}`;
}

Vue.use(Vuelidate);

Vue.mixin({
  methods,
  computed,
  http,
});

Vue.config.productionTip = false;

new Vue({
  store,
  router,
  render: (h) => h(App),
}).$mount('#app');
