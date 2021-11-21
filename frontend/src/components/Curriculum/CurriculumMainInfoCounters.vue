<template>
  <div class="counters">
    <div class="counter">
      <div class="count">{{ curriculum.budget_places }}</div>
      <div class="description">количество бюджетных мест</div>
    </div>
    <div class="counter">
      <div class="count">{{curriculum.passing_score}}</div>
      <div class="description">проходной балл на бюджет</div>
    </div>
    <div class="counter">
      <div class="count">{{curriculum.cost}}</div>
      <div class="description">стоимость года обучения</div>
    </div>
    <div class="counter">
      <div class="count">{{curriculum.duration}}</div>
      <div class="description">длительность обучения</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CurriculumMainInfoCounters',
  props: {
    curriculum: Object,
  },
};
</script>

<style scoped lang="scss">
.counters {
  margin-top: 42px;
  display: flex;
  flex-wrap: wrap;
}

.counter{
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
}
</style>