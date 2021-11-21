<template>
  <div class="counters">
    <div class="counter">
      <div class="description">зарплата в месяц</div>
      <div class="count">
        <template v-if="vacancy.salary_min">от {{ vacancy.salary_min }}</template>
        <template v-if="vacancy.salary_max"> до {{ vacancy.salary_max }}</template>
        <template v-if="vacancy.salary_negotiable"><br>По договоренности</template>
      </div>
    </div>
    <div class="counter">
      <div class="description">Требуемый опыт</div>
      <div class="count">{{ vacancy.experience_level.name }}</div>
    </div>
    <div class="counter">
      <div class="description">Занятость</div>
      <div class="count">{{ vacancy.employment_type.name }}</div>
    </div>
    <div class="counter">
      <div class="description">График работы</div>
      <div class="count">{{ vacancy.schedule.name }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'VacancyMainInfoCounters',
  props: {
    vacancy: Object,
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
  width: 100%;
  margin-bottom: 24px;
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
  margin-bottom: 4px;
}
</style>