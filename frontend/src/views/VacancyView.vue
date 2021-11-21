<template>
  <div class="inner-page curriculum" v-if="loaded">
    <Breadcrumbs ref="breadcrumbs" :margin="32"/>
    <VacancyMainInfo :vacancy="vacancy"/>
    <VacanciesSimilar :vacancy="vacancy"/>
  </div>
</template>

<script>
import VacancyMainInfo from '@/components/Vacancies/VacancyMainInfo';
import VacanciesSimilar from '@/components/Vacancies/VacanciesSimilar';

export default {
  name: 'VacancyView',
  components: {VacanciesSimilar, VacancyMainInfo},
  data() {
    return {
      loaded: false,
      vacancy: {},
    };
  },
  watch: {
    '$route'() {
      this.getVacancy();
    },
  },
  mounted() {
    this.getVacancy();
  },
  methods: {
    getVacancy() {
      this.loaded = false;
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/vacancies/${this.$route.params.id}`).
          then(({data}) => {
            this.vacancy = data.data;
          }).
          finally(() => {
            this.loaded = true;
          });
    },
  },
};
</script>

<style scoped lang="scss">
</style>
