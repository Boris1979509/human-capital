import Vue from 'vue';
import VueRouter from 'vue-router';
import store from '../store';

const MainView = () => import('../views/MainView');
const InstitutionView = () => import('../views/InstitutionView');
const TypeCatalogView = () => import('../views/TypeCatalogView');
const EduProgramsFilterView = () => import('../views/EduProgramsFilterView');
const MainViewJournal = () => import('../views/MainViewJournal');
const LoginAuthView = () => import('../views/LoginAuth/LoginAuthView');
const EmployersCatalogView = () => import('../views/EmployersCatalogView');

// Organization views
const MainViewOrganizations = () => import('../views/MainViewOrganizations');

// Journal items views
const MainViewJournalList = () => import('../views/MainViewJournalList');
const MainViewJournalNews = () => import('../views/MainViewJournalNews');
const MainViewJournalArticle = () => import('../views/MainViewJournalArticle');
const MainViewJournalEvent = () => import('../views/MainViewJournalEvent');

// Сompilations main page
const MainViewCompilations = () => import('../views/MainViewCompilations');
const MainViewCompilationsItem = () => import('../views/MainViewCompilationsItem');

// User profile views
const ProfileUserPersonalView = () =>
    import('../views/Profile/User/ProfileUserPersonalView');
const ProfileUserEducationView = () =>
    import('../views/Profile/User/ProfileUserEducationView');
const ProfileUserWorkView = () =>
    import('../views/Profile/User/ProfileUserWorkView');
const ProfileUserCalendarView = () =>
    import('../views/Profile/User/ProfileUserCalendarView');
const ProfileUserFavoritesView = () =>
    import('../views/Profile/User/ProfileUserFavoritesView');
const ProfileUserSettingsView = () =>
    import('../views/Profile/User/ProfileUserSettingsView');
const PasswordResetView = () => import('../views/LoginAuth/PasswordResetView');

// Organization profile views
const ProfileOrgInfoView = () =>
    import('../views/Profile/Organization/ProfileOrgInfoView');
const ProfileOrgProgramsView = () =>
    import('../views/Profile/Organization/ProfileOrgProgramsView');
const ProfileOrgProgramsAddView = () =>
    import('../views/Profile/Organization/ProfileOrgProgramsAddView');
const ProfileOrgEmployeesView = () =>
    import('../views/Profile/Organization/ProfileOrgEmployeesView');
const ProfileOrgEmployeesListView = () =>
    import('../views/Profile/Organization/ProfileOrgEmployeesListView');
const ProfileOrgAddEmployeeView = () =>
    import('../views/Profile/Organization/ProfileOrgAddEmployeeView');
const ProfileOrgEditEmployeeView = () =>
    import('../views/Profile/Organization/ProfileOrgEditEmployeeView');
const ProfileOrgCalendarView = () =>
    import('../views/Profile/Organization/ProfileOrgCalendarView');
const ProfileOrgJournalView = () =>
    import('../views/Profile/Organization/ProfileOrgJournalView');
const ProfileOrgJournalListView = () =>
    import('../views/Profile/Organization/ProfileOrgJournalListView');
const ProfileOrgAddJournalNewsView = () =>
    import('../views/Profile/Organization/ProfileOrgAddJournalNewsView');
const ProfileOrgEditJournalNewsView = () =>
    import('../views/Profile/Organization/ProfileOrgEditJournalNewsView');
const ProfileOrgAddJournalNoteView = () =>
    import('../views/Profile/Organization/ProfileOrgAddJournalNoteView');
const ProfileOrgEditJournalNoteView = () =>
    import('../views/Profile/Organization/ProfileOrgEditJournalNoteView');
const ProfileOrgAddJournalEventView = () =>
    import('../views/Profile/Organization/ProfileOrgAddJournalEventView');
const ProfileOrgEditJournalEventView = () =>
    import('../views/Profile/Organization/ProfileOrgEditJournalEventView');
const ProfileOrgSettingsView = () =>
    import('../views/Profile/Organization/ProfileOrgSettingsView');

// Employer profile view
const ProfileEmployerInfoView = () => import('../views/Profile/Employer/ProfileEmployerInfoView');
const ProfileEmployerSettingsView = () => import('../views/Profile/Employer/ProfileEmployerSettingsView');
const ProfileEmployerCalendarView = () => import('../views/Profile/Employer/ProfileEmployerCalendarView');
const ProfileEmployerVacanciesListView = () => import('../views/Profile/Employer/ProfileEmployerVacanciesListView');
const ProfileEmployerVacancyAddView = () => import('../views/Profile/Employer/ProfileEmployerVacancyAddView');

import CurriculumMainView from '../views/CurriculumMainView';
import JobView from '@/views/JobView';
import VacanciesCatalogView from '@/views/VacanciesCatalogView';
import EmployerView from '@/views/EmployerView';
import VacancyView from '@/views/VacancyView';
import ProfileEmployerVacanciesResponsesListView
  from '@/views/Profile/Employer/ProfileEmployerVacanciesResponsesListView';
import ProfileUserResponsesView
  from '@/views/Profile/User/ProfileUserResponsesView';
import ProfileEmployerVacancyResponses
  from '@/components/EmployerProfile/ProfileEmployerVacancyResponses';
import SearchView from '@/views/SearchView';
import EventsRegistrationsList
  from '@/components/OrgProfile/EventsRegistrationsList';
import EventRegistrationsList
  from '@/components/OrgProfile/EventRegistrationsList';
import ProfileEmpJournalView
  from '@/views/Profile/Employer/ProfileEmpJournalView';
import ProfileEmpJournalListView
  from '@/views/Profile/Employer/ProfileEmpJournalListView';
import ProfileEmpAddJournalNewsView
  from '@/views/Profile/Employer/ProfileEmpAddJournalNewsView';
import ProfileEmpEditJournalNewsView
  from '@/views/Profile/Employer/ProfileEmpEditJournalNewsView';
import ProfileEmpAddJournalEventView
  from '@/views/Profile/Employer/ProfileEmpAddJournalEventView';
import ProfileEmpEditJournalEventView
  from '@/views/Profile/Employer/ProfileEmpEditJournalEventView';

// Service
const PageNotFoundView = () => import('../views/PageNotFoundView');

Vue.use(VueRouter);

const router = new VueRouter({
  mode: 'history',
  routes: [
    {
      path: '/',
      name: 'MainView',
      props: (router) => ({api_token: router.query.api_token}),
      component: MainView,
      meta: {
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        store.dispatch('GET_INST_TYPES_FROM_SERVER', {
          clear: true,
          params: {
            type: 'inst_type',
            ids: process.env.VUE_APP_PAGE_INDEX_INST_TYPE_IDS,
          },
        }).then(() => next());
      },
    },
    {
      path: '/kids',
      name: 'TypeChildrenCatalogView',
      component: TypeCatalogView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Детям'},
        ],
        type: 'for_children',
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        store.dispatch('GET_MAX_PROGRAM_COST_FROM_SERVER', {
          clear: true,
          params: {
            filter: 'for_children',
          },
        }).then(() => {
          store.dispatch('GET_INST_TYPES_FROM_SERVER', {
            clear: true,
            params: {
              type: 'inst_type',
              ids: process.env.VUE_APP_CHILDREN_TYPE_IDS,
            },
          }).then(() => next());
        });
      },
    },
    {
      path: '/entrants',
      name: 'TypeEntrantsCatalogView',
      component: TypeCatalogView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Абитуриентам'},
        ],
        type: 'for_entrant',
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        store.dispatch('GET_MAX_PROGRAM_COST_FROM_SERVER', {
          clear: true,
          params: {
            filter: 'for_entrant',
          },
        }).then(() => {
          store.dispatch('GET_INST_TYPES_FROM_SERVER', {
            clear: true,
            params: {
              type: 'inst_type',
              ids: process.env.VUE_APP_ENTRANT_INST_TYPE_IDS,
            },
          }).then(() => next());
        });
      },
    },
    {
      path: '/adults',
      name: 'TypeAdultsCatalogView',
      component: TypeCatalogView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Взрослым'},
        ],
        type: 'for_adult',
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        store.dispatch('GET_MAX_PROGRAM_COST_FROM_SERVER', {
          clear: true,
          params: {
            filter: 'for_adult',
          },
        }).then(() => {
          store.dispatch('GET_INST_TYPES_FROM_SERVER', {
            clear: true,
            params: {
              type: 'inst_type',
              ids: process.env.VUE_APP_ADULT_INST_TYPE_IDS,
            },
          }).then(() => next());
        });
      },
    },
    {
      path: '/selections',
      name: 'MainViewCompilations',
      component: MainViewCompilations,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Подборки'},
        ],
        requiresAuth: false,
      },
    },
    {
      path: '/job',
      name: 'EmployersCatalogView',
      component: JobView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Работодатели'},
        ],
        requiresAuth: false,
      },
      children: [
        {
          path: 'employers',
          name: 'EmployersCatalogView',
          components: {
            catalog: EmployersCatalogView,
          },
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Работодатели'},
            ],
            requiresAuth: false,
          },
        },
        {
          path: 'vacancies',
          name: 'VacanciesCatalogView',
          components: {
            catalog: VacanciesCatalogView,
          },
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Работодатели'},
            ],
            requiresAuth: false,
          },
        },
      ],
    },
    {
      path: '/employers/:id',
      name: 'EmployerView',
      component: EmployerView,
      props: true,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Работодатели', link: 'EmployersCatalogView'},
        ],
        requiresAuth: false,
      },
    },
    {
      path: '/job/vacancies/:id',
      name: 'VacancyView',
      component: VacancyView,
      props: true,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Вакансии', link: 'EmployersCatalogView'},
        ],
        requiresAuth: false,
      },
    },
    {
      path: '/selections/:id',
      name: 'MainViewCompilationsItem',
      component: MainViewCompilationsItem,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Подборки'},
        ],
        requiresAuth: false,
      },
    },
    {
      path: '/programs',
      name: 'EduProgramsFilterView',
      component: EduProgramsFilterView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Каталог образования'},
        ],
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        window.scrollTo(0, 0);

        store.dispatch('GET_MAX_PROGRAM_COST_FROM_SERVER', {
          clear: true,
        }).then(() => {
          store.dispatch('GET_INST_TYPES_FROM_SERVER', {
            clear: true,
            params: {
              type: 'inst_type',
              ids: process.env.VUE_APP_ADULT_INST_TYPE_IDS,
            },
          }).then(() => next());
        });
      },
    },
    {
      path: '/institutions',
      name: 'MainViewOrganizations',
      props: (router) => ({api_token: router.query.api_token}),
      component: MainViewOrganizations,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Каталог образования'},
        ],
        requiresAuth: false,
      },
    },
    {
      path: '/institutions/:id',
      name: 'InstitutionView',
      component: InstitutionView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Каталог образования', link: 'MainViewOrganizations'},
        ],
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        window.scrollTo(0, 0);

        store.dispatch('GET_MAX_PROGRAM_COST_FROM_SERVER', {
          clear: true,
          params: {
            institution_id: to.params.id,
          },
        }).then(() => {
          store.dispatch('GET_INST_TYPES_FROM_SERVER', {
            clear: true,
            params: {
              type: 'inst_type',
              ids: process.env.VUE_APP_ADULT_INST_TYPE_IDS,
            },
          }).then(() => next());
        });
      },
    },
    {
      path: '/programs/:id',
      name: 'Curriculum',
      component: CurriculumMainView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Каталог образования', link: 'MainViewOrganizations'},
        ],
        requiresAuth: false,
      },
    },
    {
      path: '/journal/',
      name: 'MainViewJournal',
      component: MainViewJournal,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Журнал'},
        ],
        requiresAuth: false,
      },
      children: [
        {
          path: '/',
          name: 'MainViewJournalList',
          component: MainViewJournalList,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Журнал'},
            ],
            requiresAuth: false,
          },
        },
        {
          path: 'article/:id',
          name: 'MainViewJournalArticle',
          component: MainViewJournalArticle,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Журнал', link: 'MainViewJournalList'},
            ],
            requiresAuth: false,
          },
        },
        {
          path: 'news/:id',
          name: 'MainViewJournalNews',
          component: MainViewJournalNews,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Журнал', link: 'MainViewJournalList'},
            ],
            requiresAuth: false,
          },
        },
        {
          path: 'event/:id',
          name: 'MainViewJournalEvent',
          component: MainViewJournalEvent,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Журнал', link: 'MainViewJournalList'},
            ],
            requiresAuth: false,
          },
        },
      ],
    },
    {
      path: '/profile/user',
      redirect: '/profile/user/personal',
      meta: {
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/org',
      redirect: '/profile/org/info',
      meta: {
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/employer',
      redirect: '/profile/employer/info',
      meta: {
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/login',
      name: 'LoginAuthView',
      component: LoginAuthView,
      meta: {
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        if (store.getters.GET_LOGIN_STATUS) {
          next('/');
        } else {
          next();
        }
      },
    },
    {
      path: '/login/reset',
      name: 'PasswordResetView',
      component: PasswordResetView,
      meta: {
        requiresAuth: false,
      },
      beforeEnter: (to, from, next) => {
        if (store.getters.GET_LOGIN_STATUS) {
          next('/');
        } else {
          next();
        }
      },
    },
    {
      path: '/profile/user/personal',
      name: 'ProfileUserPersonalView',
      component: ProfileUserPersonalView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: MainView},
          {name: 'Профиль'},
          {name: 'Личные данные'},
        ],
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/user/education',
      name: 'ProfileUserEducationView',
      component: ProfileUserEducationView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: MainView},
          {name: 'Профиль'},
          {name: 'Образование'},
        ],
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/user/work',
      name: 'ProfileUserWorkView',
      component: ProfileUserWorkView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: MainView},
          {name: 'Профиль'},
          {name: 'Работа'},
        ],
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/user/responses',
      name: 'ProfileUserResponsesView',
      component: ProfileUserResponsesView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: MainView},
          {name: 'Профиль'},
        ],
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/user/calendar',
      name: 'ProfileUserCalendarView',
      component: ProfileUserCalendarView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: MainView},
          {name: 'Профиль'},
          {name: 'Календарь'},
        ],
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/user/favorites',
      name: 'ProfileUserFavoritesView',
      component: ProfileUserFavoritesView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: MainView},
          {name: 'Профиль'},
          {name: 'Избранное'},
        ],
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/user/settings',
      name: 'ProfileUserSettingsView',
      component: ProfileUserSettingsView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: MainView},
          {name: 'Профиль'},
          {name: 'Настройки'},
        ],
        requiresAuth: true,
        permissions: 'user',
      },
    },
    {
      path: '/profile/org/info',
      name: 'ProfileOrgInfoView',
      component: ProfileOrgInfoView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Информация'},
        ],
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/org/programs',
      name: 'ProfileOrgProgramsView',
      component: ProfileOrgProgramsView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Программы обучения'},
        ],
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/org/programs/add',
      name: 'ProfileOrgProgramsAddView',
      component: ProfileOrgProgramsAddView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Программы обучения'},
        ],
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/org/programs/edit/:institution/:curriculum',
      name: 'ProfileOrgProgramsAddByIdView',
      component: ProfileOrgProgramsAddView,
      beforeEnter: (to, from, next) => {
        store.dispatch('GET_PROGRAM_FROM_SERVER', {
          clear: true,
          params: {
            institution: to.params.institution,
            curriculum: to.params.curriculum,
          },
        }).then(() => next());
      },
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Программы обучения'},
        ],
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/org/calendar',
      name: 'ProfileOrgCalendarView',
      component: ProfileOrgCalendarView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Календарь'},
        ],
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/org/employees',
      name: 'ProfileOrgEmployeesView',
      component: ProfileOrgEmployeesView,
      children: [
        {
          path: '',
          name: 'ProfileOrgEmployeesListView',
          component: ProfileOrgEmployeesListView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Ключевые сотрудники'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'add-employee',
          name: 'ProfileOrgAddEmployeeView',
          component: ProfileOrgAddEmployeeView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {
                name: 'Ключевые сотрудники',
                link: 'ProfileOrgEmployeesListView',
              },
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'edit-employee/:id',
          name: 'ProfileOrgEditEmployeeView',
          component: ProfileOrgEditEmployeeView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {
                name: 'Ключевые сотрудники',
                link: 'ProfileOrgEmployeesListView',
              },
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
      ],
      meta: {
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/org/journal',
      name: 'ProfileOrgJournalView',
      component: ProfileOrgJournalView,
      children: [
        {
          path: '',
          name: 'ProfileOrgJournalListView',
          component: ProfileOrgJournalListView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'add-news',
          name: 'ProfileOrgAddJournalNewsView',
          component: ProfileOrgAddJournalNewsView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'edit-news/:id',
          name: 'ProfileOrgEditJournalNewsView',
          component: ProfileOrgEditJournalNewsView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'add-note',
          name: 'ProfileOrgAddJournalNoteView',
          component: ProfileOrgAddJournalNoteView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'edit-note/:id',
          name: 'ProfileOrgEditJournalNoteView',
          component: ProfileOrgEditJournalNoteView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'add-event',
          name: 'ProfileOrgAddJournalEventView',
          component: ProfileOrgAddJournalEventView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'edit-event/:id',
          name: 'ProfileOrgEditJournalEventView',
          component: ProfileOrgEditJournalEventView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: 'events/registrations',
          name: 'ProfileOrgRegistrationsListView',
          component: EventsRegistrationsList,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
        },
        {
          path: ':id/registrations',
          name: 'ProfileOrgEventRegistrationsList',
          component: EventRegistrationsList,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileOrgJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'organization',
          },
          props: true
        },
      ],
      meta: {
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/employer/journal',
      name: 'ProfileEmpJournalView',
      component: ProfileEmpJournalView,
      children: [
        {
          path: '',
          name: 'ProfileEmpJournalListView',
          component: ProfileEmpJournalListView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал'},
            ],
            requiresAuth: true,
            permissions: 'employer',
          },
        },
        {
          path: 'add-news',
          name: 'ProfileEmpAddJournalNewsView',
          component: ProfileEmpAddJournalNewsView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileEmpJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'employer',
          },
        },
        {
          path: 'edit-news/:id',
          name: 'ProfileEmpEditJournalNewsView',
          component: ProfileEmpEditJournalNewsView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileEmpJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'employer',
          },
        },
        {
          path: 'add-event',
          name: 'ProfileEmpAddJournalEventView',
          component: ProfileEmpAddJournalEventView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileEmpJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'employer',
          },
        },
        {
          path: 'edit-event/:id',
          name: 'ProfileEmpEditJournalEventView',
          component: ProfileEmpEditJournalEventView,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileEmpJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'employer',
          },
        },
        {
          path: 'events/registrations',
          name: 'ProfileEmpRegistrationsListView',
          component: EventsRegistrationsList,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileEmpJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'employer',
          },
        },
        {
          path: ':id/registrations',
          name: 'ProfileEmpEventRegistrationsList',
          component: EventRegistrationsList,
          meta: {
            breadcrumb: [
              {name: 'Главная', link: 'MainView'},
              {name: 'Профиль'},
              {name: 'Журнал', link: 'ProfileEmpJournalListView'},
            ],
            requiresAuth: true,
            permissions: 'employer',
          },
          props: true
        },
      ],
      meta: {
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/org/settings',
      name: 'ProfileOrgSettingsView',
      component: ProfileOrgSettingsView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Настройки'},
        ],
        requiresAuth: true,
        permissions: 'organization',
      },
    },
    {
      path: '/profile/employer/info',
      name: 'ProfileEmployerInfoView',
      component: ProfileEmployerInfoView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Общая инаормация'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/employer/calendar',
      name: 'ProfileEmployerCalendarView',
      component: ProfileEmployerCalendarView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Календарь'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/employer/vacancies',
      name: 'ProfileEmployerVacanciesListView',
      component: ProfileEmployerVacanciesListView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Вакансии'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/employer/vacancies/responses',
      name: 'ProfileEmployerVacanciesResponsesListView',
      component: ProfileEmployerVacanciesResponsesListView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Вакансии'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/employer/vacancies/add',
      name: 'ProfileEmployerVacancyAddView',
      component: ProfileEmployerVacancyAddView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Вакансии'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/employer/vacancies/edit/:id',
      name: 'ProfileEmployerVacancyAddView',
      component: ProfileEmployerVacancyAddView,
      props: true,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Вакансии'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/employer/vacancies/:id/responses',
      name: 'ProfileEmployerVacancyResponses',
      component: ProfileEmployerVacancyResponses,
      props: true,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Вакансии'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/profile/employer/settings',
      name: 'ProfileEmployerSettingsView',
      component: ProfileEmployerSettingsView,
      meta: {
        breadcrumb: [
          {name: 'Главная', link: 'MainView'},
          {name: 'Профиль'},
          {name: 'Настройки'},
        ],
        requiresAuth: true,
        permissions: 'employer',
      },
    },
    {
      path: '/search',
      name: 'search',
      component: SearchView,
      meta: {
        requiresAuth: false,
      },
    },
    {
      path: '/:pathMatch(.*)*',
      component: PageNotFoundView,
      meta: {
        requiresAuth: false,
      },
    },
  ],
  scrollBehavior() {
    return {x: 0, y: 0};
  },
});

router.beforeEach(async (to, from, next) => {
  if (to.matched.some((record) => record.meta.requiresAuth)) {
    if (!store.getters.GET_LOGIN_STATUS) {
      next('/login');
    } else {
      await store.dispatch('GET_USER_FROM_SERVER').then(() => {
        if (store.getters.GET_USER.type !== null) {
          if (to.meta.permissions === 'user') {
            if (store.getters.GET_USER.type == '1') {
              next();
            } else {
              next('/');
            }
          } else if (to.meta.permissions === 'organization') {
            if (store.getters.GET_USER.type == '2') {
              next();
            } else {
              next('/');
            }
          } else if (to.meta.permissions === 'employer') {
            if (store.getters.GET_USER.type == '3') {
              next();
            } else {
              next('/');
            }
          } else {
            next();
          }
        }
      });
    }
  } else {
    next();
  }
});

export default router;
