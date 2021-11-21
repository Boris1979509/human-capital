<template>
  <div id="app">
    <Sprite/>

    <div class="portal-container" >
      <Header/>

      <div class="content">
        <div
          class="wrapper"
          :class="{'wide_layout': isWideLayout}"
        >
          <router-view/>
        </div>
      </div>

      <Footer/>
    </div>
  </div>
</template>

<script>
export default {
  name: 'App',

  mounted() {
    if (this.isLoggedIn) {
      this.login();
    }
  },

  computed: {
    isLoggedIn: function() {
      return this.$store.getters.GET_LOGIN_STATUS;
    },
    isWideLayout: function() {
      return (this.$route.name === 'ProfileOrgEmployeesListView' ||
          this.$route.name === 'ProfileOrgJournalListView' ||
          this.$route.name === 'ProfileOrgCalendarView' ||
          this.$route.name === 'ProfileUserCalendarView') ||
          this.$route.name === 'ProfileEmployerCalendarView';
    }
  },

  created() {
    this.$store.dispatch('GET_CITIES_LIST_FROM_SERVER');
    this.$store.dispatch('GET_UNIVERSITIES_LIST_FROM_SERVER');
    this.$store.dispatch('GET_DICTIONARIES_LIST_FROM_SERVER');
    this.$store.dispatch('GET_PROFESSIONS_LIST_FROM_SERVER');
    this.$store.dispatch('GET_REGION_DATA_FROM_SERVER');
    this.$store.dispatch('GET_REGION_NAME_FROM_SERVER');
    this.$store.dispatch('GET_SELECTIONS_LIST_FROM_SERVER');
  },

  methods: {
    login() {
      this.$store.dispatch('GET_USER_FROM_SERVER');
      this.$store.dispatch('GET_ORG_DATA_FROM_SERVER');
      this.$store.dispatch('GET_EMPLOYER_DATA_FROM_SERVER');
    },
  },

  watch: {
    '$route.query'(){
      const apiToken = this.$route.query.api_token;
      if (apiToken) {
        this.$router.replace({ query: null });
        this.$store.dispatch('LOG_ME_IN_KEYKLOAK', apiToken);
        this.$store.dispatch('GET_USER_FROM_SERVER');
      }
    },
    isLoggedIn: function(newVal) {
      if (newVal) {
        this.login();
      }
    },
  }
}
</script>

<style lang="scss">
@import './assets/scss/app';
</style>
