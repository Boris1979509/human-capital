<template>
  <div class="organizations-counters">
    <div class="organizations-counters__item" v-for="(counter, index, i) in employerCounters" :key="index">
      <div class="organizations-counters__item__count">
        {{ counter }}
      </div>

      <div class="organizations-counters__item__name">
        {{ getCounterName(index, i) }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmployersCounters',

  created() {
    this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/employer/summary`).
        then(({data}) => this.employerCounters = data);
  },

  data() {
    return {
      employerCounters: [],
    };
  },

  methods: {
    getCounterName(index, i) {
      switch (index) {
        case 'employers_count':
          return this.$num2str(i, ['Работодатель', 'Работодателя', 'Работодателей']);
        case 'vacancies_count':
          return this.$num2str(i, ['Вакансия', 'Вакансии', 'Вакансий']);
        default:
          return '';
      }
    },
  },
};
</script>
