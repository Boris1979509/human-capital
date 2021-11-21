<template>
  <a @click="$router.push(`/employers/${employer.id}`)" class="organization-card">
    <div class="organization-card__avatar">
      <img :src="employer.avatar.url" :alt="employer.name" v-if="employer.avatar">
    </div>
    <div class="organization-card__name">{{ employer.name }}</div>
    <div class="organization-card__type">{{ employer.branch.name }}</div>
    <div class="organization-card__curricula_count">{{ vacanciesCount }}</div>
  </a>
</template>

<script>
export default {
  name: 'EmployerCard',

  props: {
    employer: Object,
  },

  computed: {
    vacanciesCount() {
      return this.employer.vacancies_count + ' ' +
          this.$num2str(this.employer.vacancies_count, ['Вакансия', 'Вакансии', 'Вакансий']);
    },
  },
};
</script>

<style>
.organization-card {
  cursor: pointer;
  position: relative;
}
</style>
