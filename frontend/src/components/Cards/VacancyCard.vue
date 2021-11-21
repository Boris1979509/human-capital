<template>
  <div @click="$router.push(cardUrl)" class="card">
    <div class="city">г. {{ vacancy.city.name }}</div>
    <div class="name">{{ vacancy.name }}</div>
    <div class="meta1">
      <div class="internship" v-if="vacancy.is_internship">Стажировка</div>
      <div class="salary"> от {{ vacancy.salary_min }} ₽</div>
    </div>
    <div class="meta2">
      {{ vacancy.employer.name }} • {{ vacancy.created_at | date }}
    </div>
    <div class="description" v-line-clamp="2">{{ vacancy.responsibilities }}</div>
  </div>
</template>

<script>
export default {
  name: 'VacancyCard',

  props: {
    vacancy: Object,
    detailUrl: {
      type: String,
    },
  },
  computed: {
    cardUrl() {
      return this.detailUrl || `/job/vacancies/${this.vacancy.id}`;
    },
  },

};
</script>

<style scoped lang="scss">
.city {
  font-size: 13px;
  line-height: 16px;
  color: #04153E;
  opacity: 0.72;
  margin-bottom: 8px;
}

.name {
  font-weight: 800;
  font-size: 20px;
  line-height: 24px;
  color: #04153E;
  margin-bottom: 12px;
}

.meta1 {
  display: flex;
  flex-direction: row;
  margin-bottom: 36px;
}

.meta2 {
  font-size: 13px;
  line-height: 16px;
  color: #04153E;
  opacity: 0.48;
  margin-bottom: 8px;
}

.description {
  font-size: 16px;
  line-height: 24px;
  color: #04153E;
  opacity: 0.72;
}

.internship {
  background: #E9A35D;
  border-radius: 16px;
  width: 103px;
  height: 24px;
  padding: 4px 12px;
  font-size: 13px;
  line-height: 16px;
  color: #FFFFFF;
  margin-right: 16px;
}

.salary {
  font-weight: 800;
  font-size: 16px;
  line-height: 20px;
  color: #3D75E4;
}

.card {
  cursor: pointer;
  position: relative;
  display: flex;
  flex-direction: column;

  background: var(--color-rush);
  border-radius: 16px;
  padding: 20px;

  margin-top: 32px;
  height: 280px;
  width: 100%;

  &:hover {
    box-shadow: 0 9px 28px rgba(17, 48, 121, 0.18);
  }
}
</style>
