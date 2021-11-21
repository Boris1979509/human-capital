<template>
  <div class="profile__container">
    <h4 class="title">
      Вакансии
      <span class="responses-link" @click="$router.push({name: 'ProfileEmployerVacanciesResponsesListView'})">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M12.4472 0.105792C11.9532 -0.141197 11.3526 0.059027 11.1056 0.553005C10.8586 1.04698 11.0588 1.64766 11.5528 1.89465C11.889 2.06277 12.312 2.474 12.6835 2.91858C12.8831 3.15747 13 3.49949 13 3.90855V14.0002H3V2.00022H8.5V3.50022C8.5 4.60479 9.39543 5.50022 10.5 5.50022H11.5C12.0523 5.50022 12.5 5.0525 12.5 4.50022C12.5 3.94793 12.0523 3.50022 11.5 3.50022H10.5V2.00022C10.5 0.89565 9.60457 0.000219044 8.5 0.000219044H3C1.89543 0.000219044 1 0.89565 1 2.00022V14.0002C1 15.1048 1.89543 16.0002 3 16.0002H13C14.1046 16.0002 15 15.1048 15 14.0002V3.90855C15 3.13305 14.7781 2.30617 14.2182 1.6361C13.8261 1.16691 13.18 0.472186 12.4472 0.105792Z"
              fill="#214EB0"/>
          <path
              d="M11.2593 8.65105C11.6187 8.23173 11.5701 7.60043 11.1508 7.24101C10.7315 6.88158 10.1002 6.93015 9.74074 7.34947L7.44352 10.0296L6.20711 8.79315C5.81658 8.40263 5.18342 8.40263 4.79289 8.79315C4.40237 9.18368 4.40237 9.81684 4.79289 10.2074L6.79289 12.2074C6.98985 12.4043 7.26004 12.5102 7.53838 12.4995C7.81672 12.4888 8.07798 12.3625 8.25926 12.1511L11.2593 8.65105Z"
              fill="#214EB0"/>
        </svg>
        Отклики соискателей
      </span>
    </h4>
    <Button @click.native="$router.push({name: 'ProfileEmployerVacancyAddView'});"
            class="btn--light"
            style="margin-bottom: 24px;">
      <Icon xlink="plus"
            viewport="0 0 16 16"/>
      Добавить вакансию
    </Button>

    <Loading v-if="isLoading"/>

    <template v-if="vacancies.length>0">
      <div class="row">
        <div class="col-50" v-for="vacancy in vacancies" :key="vacancy.id">
          <VacancyCard :vacancy="vacancy" :detailUrl="`/profile/employer/vacancies/edit/${vacancy.id}`"/>
        </div>
      </div>
    </template>

    <NothingFound v-else-if="vacancies.length===0" text="Не найдено ни одной вакансии"/>

    <modal ref="deleteVacancy" :is-default-close="false">
      <template v-slot:header>
        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M3 28C3 14.1929 14.1929 3 28 3C41.8071 3 53 14.1929 53 28C53 33.6309 51.1402 38.823 48.0008 43.0016C47.5032 43.6639 47.6367 44.6042 48.299 45.1018C48.9613 45.5994 49.9016 45.4659 50.3992 44.8036C53.9156 40.1233 56 34.3029 56 28C56 12.536 43.464 0 28 0C12.536 0 0 12.536 0 28C0 43.464 12.536 56 28 56C34.3017 56 40.121 53.9165 44.8009 50.4013C45.4633 49.9038 45.5969 48.9635 45.0993 48.3011C44.6018 47.6387 43.6615 47.5051 42.9991 48.0026C38.821 51.1409 33.6298 53 28 53C14.1929 53 3 41.8071 3 28Z"
              fill="#E14761"/>
          <path
              d="M28 37C27.1716 37 26.5 36.3284 26.5 35.5V12C26.5 11.1716 27.1716 10.5 28 10.5C28.8284 10.5 29.5 11.1716 29.5 12V35.5C29.5 36.3284 28.8284 37 28 37Z"
              fill="#E14761"/>
          <path
              d="M26 42C26 40.8954 26.8954 40 28 40C29.1046 40 30 40.8954 30 42C30 43.1046 29.1046 44 28 44C26.8954 44 26 43.1046 26 42Z"
              fill="#E14761"/>
        </svg>
      </template>

      <template v-slot:body>
        <h2 class="modal__title title">
          Удалить вакансию?
        </h2>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <Button class="btn btn--red" @click.native="deleteVacancy(currentDeleteId)">
            Удалить
          </Button>
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
import VacancyCard from '@/components/Cards/VacancyCard';

export default {
  name: 'VacanciesList',
  components: {VacancyCard},
  data: function() {
    return {
      vacancies: [],
      isLoading: true,
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
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/vacancies?employer_id=${this.$employer.id}`).
          then(res => {
            this.vacancies = res.data.data;
          }).
          finally(() => {
            this.isLoading = false;
          });
    },
  },
};
</script>

<style lang="scss" scoped>
.responses-link {
  margin-left: 24px;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  text-align: right;
  color: #214EB0;
  cursor: pointer;

  svg {
    margin-bottom: -2px;
  }
}
</style>
