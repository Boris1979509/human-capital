<template>
  <div class="profile__container">
    <h4 class="title">Отклики на вакансии</h4>
    <Loading v-if="isLoading"/>
    <table class="resp-tab" v-else-if="vacancies.length > 0">
      <thead>
      <tr>
        <th>Вакансия</th>
        <th>Город</th>
        <th>Статус вакансии</th>
        <th>Просмотры</th>
        <th>Отклики</th>
      </tr>
      </thead>

      <tbody>
      <VacancyRowForEmployer v-for="vacancy in vacancies" :key="vacancy.id" :vacancy="vacancy"/>
      </tbody>
    </table>

    <NothingFound v-else text="Не найдено ни одной вакансии"/>


  </div>
</template>

<script>
import VacancyRowForEmployer from '@/components/EmployerProfile/VacancyRowForEmployer';

export default {
  name: 'VacanciesResponsesList',
  components: {VacancyRowForEmployer},
  data: function() {
    return {
      isLoading: true,
      vacancies: [],
    };
  },
  watch: {
    $employer() {
      this.getVacancies();
    },
  },

  mounted() {
    this.getVacancies();
  },

  methods: {
    getVacancies() {
      this.isLoading = true;
      if (!this.$employer.id) {
        return;
      }
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/${this.$employer.id}/vacancies`).
          then(res => {
            this.vacancies = res.data.data;
          }).finally(() => {
        this.isLoading = false;
      });
    },
  },
};
</script>

<style lang="scss">
.resp-tab {
  border-radius: 5px;
  font-weight: normal;
  border: none;
  border-collapse: collapse;
  width: 100%;
  max-width: 100%;

  &__main {

    &--info {
      display: flex;
      align-items: center;

      margin-top: 4px;

      span {
        font-weight: normal;
        font-size: 13px;
        line-height: 16px;
        color: var(--main-color-dark-trans-light);
      }
    }
  }

  &__row {

    &:hover {
      background: #F6F9FE;
      border-radius: 8px;
      cursor: pointer;
    }
  }
}

.resp-tab th, .resp-tab td {
  padding: 4px 10px;
  border: none;
}

.resp-tab th {
  font-weight: 500;
  font-size: 13px;
  line-height: 16px;
  color: var(--main-color-dark-trans-light);
  text-align: left;
  vertical-align: top;
}

.resp-tab td {
  vertical-align: middle;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: var(--main-color-dark);
}
</style>
