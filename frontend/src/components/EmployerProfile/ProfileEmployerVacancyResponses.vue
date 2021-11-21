<template>
  <ProfileEmployerWrapper>
    <div class="profile__container">
      <h4 class="title">
        {{ vacancy.name }}
      </h4>
      <div class="subtitle">{{
          responsesCount + ' ' + $num2str(responsesCount, ['Отклик', 'Отклика', 'Откликов'])
        }}
      </div>
      <VacancyResponse @response-status-changed="getVacancyResponses" :response="response" :vacancy="vacancy" v-for="response in responses" :key="response.id"/>
    </div>
  </ProfileEmployerWrapper>
</template>

<script>

import VacancyResponse from '@/components/EmployerProfile/VacancyResponse';

export default {
  name: 'ProfileEmployerVacancyResponses',

  components: {VacancyResponse},
  data() {
    return {
      vacancy: {name: ''},
      responses: [],
    };
  },
  props: {
    id: [String, Number],
  },
  computed: {
    responsesCount() {
      return this.responses.length;
    },
  },
  mounted() {
    this.getVacancyResponses();
  },
  methods: {
    getVacancyResponses() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/vacancies/${this.id}/responses`).
          then(res => {
            this.vacancy = res.data.vacancy;
            this.responses = res.data.data;
          });
    },
  },
};
</script>
<style scoped>
.subtitle {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.72;
  margin-bottom: 32px;
}

.title {
  margin-bottom: 16px !important;
}
</style>