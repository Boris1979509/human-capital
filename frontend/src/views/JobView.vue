<template>
  <div class="page">
    <Breadcrumbs :margin="24"/>

    <div class="row">
      <div class="col-58">
        <h1 class="organizations-head-section__title">
          Работа
        </h1>

        <div class="organizations-head-section__description">
          Найдите работу, которая станет любимой: мы собрали лучших работодателей и самые актуальные вакансии региона
        </div>
      </div>
      <div class="col-41">
        <EmployerCounters />
      </div>
    </div>
    <Tabs :tabs="tabs" class="tabs-left"/>

    <div class="row">
      <div class="col-100">
        <router-view name="catalog"/>
      </div>
    </div>
  </div>
</template>

<script>
import EmployerCounters from '@/components/EmployerParts/EmployersCounters';

export default {
  name: 'JobView',

  components: {
    EmployerCounters,
  },

  data() {
    return {
      tabs: [
        {
          id: 1,
          name: 'Работодатели',
          route: '/job/employers/',
        },
        {
          id: 2,
          name: 'Вакансии',
          route: '/job/vacancies/',
        },
      ]

    }
  },

  methods: {

  }
}
</script>
<style scoped>
.tabs-left{
  align-items: start !important;
  justify-content: start !important;
  margin-top: 32px;
  margin-bottom: 32px !important;
}
</style>