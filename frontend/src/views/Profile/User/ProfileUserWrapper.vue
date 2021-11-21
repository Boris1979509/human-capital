<template>
  <div class="profile">
    <Breadcrumbs/>

    <div class="user">
      <Avatar :id="user_id" :progress="currentProgress" type="user"/>

      <div class="user__info">
        <h1 class="title" v-if="first_name && last_name">
          {{ first_name }} {{ last_name }}
        </h1>

        <div class="user__progress">
          Профиль заполнен на {{ currentProgress }}%

          <Tooltip activator-hover>
            <div slot="activator">
              <Icon xlink="info"
                    viewport="0 0 16 16"
              />
            </div>

            <span>Добавьте недостающие данные, чтобы заполнить шкалу</span>
          </Tooltip>
        </div>
      </div>
    </div>

    <Tabs :tabs="tabs"/>

    <slot v-bind:favoritesCount="favoritesCount"/>
  </div>
</template>

<script>
import Avatar from '@/components/InputComponents/Avatar';
import Tooltip from '@/components/Tooltip';

export default {
  name: 'ProfileUserWrapper',

  components: {
    Avatar,
    Tooltip,
  },

  computed: {
    user: function() {
      return this.$user;
    },
  },

  watch: {
    user: function(val) {
      this.first_name = val?.first_name;
      this.last_name = val?.last_name;
      this.user_id = val?.id;
      this.currentProgress = val?.progress;
    },
  },

  mounted() {
    if (this.user) {
      this.first_name = this.user?.first_name;
      this.last_name = this.user?.last_name;
      this.user_id = this.user?.id;
      this.currentProgress = this.user?.progress;
      this.updateFavoritesCount();
    }
  },

  data: function() {
    return {
      user_id: '',
      first_name: '',
      last_name: '',

      currentProgress: 0,

      tabs: [
        {
          id: 1,
          name: 'Личные данные',
          route: '/profile/user/personal',
        },
        {
          id: 2,
          name: 'Образование',
          route: '/profile/user/education',
        },
        {
          id: 3,
          name: 'Работа',
          route: '/profile/user/work',
        },
        {
          id: 4,
          name: 'Отклики',
          route: '/profile/user/responses',
        },
        {
          id: 5,
          name: 'Календарь',
          route: '/profile/user/calendar',
          //counter: 3,
        },
        {
          id: 6,
          name: 'Избранное',
          route: '/profile/user/favorites',
          counter: 0,
        },
        {
          id: 7,
          name: 'Настройки',
          route: '/profile/user/settings',
        },
      ],
      favoritesCount: 0,
    };
  },
  methods: {
    async getFavoritesCount() {
      return this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/favorites/count`).then(res => res.data);
    },
    async updateFavoritesCount() {
      this.favoritesCount = await this.getFavoritesCount();
      let favoritesTab = this.tabs.filter(tab => tab.id === 5)[0];
      favoritesTab.counter = this.favoritesCount;
    },
  },
};
</script>
