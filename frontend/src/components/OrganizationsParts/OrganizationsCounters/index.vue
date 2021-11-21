<template>
  <div class="organizations-counters">
    <div class="organizations-counters__item" v-for="(counter, index) in organizationsCounters" :key="index">
      <div class="organizations-counters__item__count">{{ counter.count }}</div>
      <div class="organizations-counters__item__name">{{ getCounterName(counter.count, counter.id) }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'OrganizationsHeadSectionCounters',

  methods: {
    getCounterName(count, id) {
      switch (id) {
        case 'INSTITUTION_COUNT':
          return this.$num2str(count, ['Учебное заведение', 'Учебных заведения', 'Учебных заведений']);
        case 'CURRICULUM_COUNT':
          return this.$num2str(
              count,
              ['Образовательная программа', 'Образовательные программы', 'Образовательных программ'],
          );
        default:
          return '';
      }
    },
  },
  data() {
    return {
      organizationsCounters: [],
    };
  },
  created() {
    this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions/summary`).
        then(({data}) => this.organizationsCounters = data.data);
  },
};
</script>
