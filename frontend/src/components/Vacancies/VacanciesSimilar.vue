<template>
  <div class="container">
    <h1>Похожие вакансии</h1>
    <div class="row" style="min-height: 330px">
      <div class="col-50" v-for="vacancy in vacancies" :key="vacancy.id">
        <VacancyCard :vacancy="vacancy"/>
      </div>
    </div>
  </div>
</template>

<script>
import VacancyCard from '@/components/Cards/VacancyCard';

export default {
  name: 'VacanciesSimilar',
  components: {VacancyCard},
  props: {
    vacancy: Object,
  },
  data() {
    return {
      pagination: {
        limit: 2,
        page: 1,
      },
      vacancies: [],
    };
  },
  mounted() {
    this.getSimilarVacancies();
  },
  methods: {
    getSimilarVacancies() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/vacancies/${this.vacancy.id}/similar`, {
        params: {
          ...this.pagination,
        },
      }).
          then(({data}) => {
            this.vacancies = data.data;
          });
    },
  },

};
</script>

<style scoped lang="scss">
h1 {
  font-weight: 800;
  font-size: 32px;
  line-height: 36px;
  color: #04153E;
}

.container {
  margin-top: 110px;
}
</style>