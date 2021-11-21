<template>
  <div class="counters">
    <div class="counter" v-if="employer.show_vacancies_count">
      <div class="count">{{ employer.vacancies_count }}</div>
      <div class="description">открытых вакансий</div>
    </div>
    <div class="counter">
      <div class="count">{{ employer.internships }}</div>
      <div class="description">стажировки</div>
    </div>
    <div class="counter">
      <div class="count">{{ employer.count_employees }}</div>
      <div class="description">сотрудника</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmployerMainInfoCounters',
  props: {
    employer: Object,
  },
};
</script>

<style scoped lang="scss">
.counters {
  margin-top: 42px;
  display: flex;
  flex-wrap: wrap;
}

.counter {
  width: 50%;
  margin-bottom: 24px;
  max-width: 131px;
}

.count {
  font-style: normal;
  font-weight: 800;
  font-size: 16px;
  line-height: 20px;
  color: #3D75E4;
}

.description {
  font-style: normal;
  font-weight: normal;
  font-size: 13px;
  line-height: 16px;
  color: #091F52;
  opacity: 0.72;
}
</style>