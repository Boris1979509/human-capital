<template>
  <header class="header">
    <div class="header__container">
      <router-link to="/">
        <Logo/>
      </router-link>

      <nav class="header__nav">
        <router-link to="/" class="header__nav-link">
          Профессии
        </router-link>

        <router-link to="/" class="header__nav-link">
          Мнения
        </router-link>

        <router-link to="/institutions" class="header__nav-link">
          Каталог образования
        </router-link>

        <router-link to="/job/employers" class="header__nav-link">
          Работодатели
        </router-link>

        <router-link to="/journal" class="header__nav-link">
          Журнал
        </router-link>
      </nav>

      <Button v-if="loginStatus" @click.native="toProfile" class="header__profile">
        <div class="header__avatar">
          <img :src="avatar ? avatar : require('@/assets/svg/user.svg')" alt="Профиль">
        </div>
        Профиль
      </Button>
      <template v-else>
        <Button @click.native="toLogin" class="header__profile">
          Войти через почту
        </Button>
        <Button @click.native="toLoginElk" class="header__profile">
          Войти через ЕЛК
        </Button>
      </template>

    </div>
  </header>
</template>

<script>
export default {
  name: 'Header',

  computed: {
    orgInfo: function() {
      return this.$organization[0];
    },
    user: function() {
      return this.$user;
    },
    loginStatus: function() {
      return this.$loginStatus;
    },
    userAvatar: function() {
      return this.$getUserAvatar;
    },
    orgAvatar: function() {
      return this.$getOrgAvatar;
    },
    employerAvatar: function() {
      return this.$getEmployerAvatar;
    },
  },

  watch: {
    user: function(val) {
      this.type = val?.type;
    },
    userAvatar: function(val) {
      if (this.type == '1') this.avatar = val;
    },
    orgAvatar: function(val) {
      if (this.type == '2') this.avatar = val;
    },
    employerAvatar: function(val) {
      if (this.type == '3') this.avatar = val;
    },
  },

  mounted() {
    this.type = this.user?.type;

    if (this.type == '1') this.avatar = this.userAvatar || require('@/assets/svg/user.svg');
    if (this.type == '2') this.avatar = this.orgAvatar || require('@/assets/svg/user.svg');
    if (this.type == '3') this.avatar = this.employerAvatar || require('@/assets/svg/user.svg');
  },

  data: function() {
    return {
      avatar: null,
      type: null,
    };
  },

  methods: {
    toProfile: function() {
      if (!this.$route.path.includes('profile')) {
        if (this.type == '1') {
          this.$router.push('/profile/user/personal');
        }
        if (this.type == '2') {
          this.$router.push('/profile/org/info');
        }
        if (this.type == '3') {
          this.$router.push('/profile/employer/info');
        }
      }
    },

    toLogin: function() {
      this.$router.push('/login');
    },
    toLoginElk(){
      window.location.href = `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}login`
    }
  },
};
</script>
