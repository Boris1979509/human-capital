<template>
  <div class="competitions">
    <h1>Компетенции</h1>
    <div class="list">
      <div class="competition" v-for="competition in competitions" :key="competition">{{ competition }}</div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'CurriculumMainInfoCompetitions',
  props: {
    competitions: Array,
  },
};
</script>

<style scoped lang="scss">
.competitions {
  margin-top: 32px;
}

h1 {
  font-style: normal;
  font-weight: 800;
  font-size: 24px;
  line-height: 28px;
  margin-bottom: 16px;
}

.list {
  display: flex;
  flex-wrap: wrap;
}

.competition {
  display: inline-block;
  padding: 4px 12px;
  background: #81ABEE;
  border-radius: 16px;
  color: #FFF;
  margin-right: 8px;
  margin-bottom: 8px;
}
</style>