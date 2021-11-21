<template>
  <div class="inner-page curriculum" v-if="loaded">
    <Breadcrumbs ref="breadcrumbs" :margin="32"/>
    <EmployerMainInfo :employer="employer"/>
    <EmployerVacanciesCatalog :employer="employer"/>
    <EmployerContacts :employer="employer"/>
    <EmployerJournal :employer="employer"/>
  </div>
</template>

<script>
import EmployerMainInfo from '@/components/EmployerParts/EmployerMainInfo';
import EmployerVacanciesCatalog from '@/components/Vacancies/EmployerVacanciesCatalog';
import EmployerContacts from '@/components/EmployerParts/EmployerContacts';
import EmployerJournal from '@/components/EmployerParts/EmployerJournal';

export default {
  name: 'EmployerView',
  components: {EmployerJournal, EmployerContacts, EmployerVacanciesCatalog, EmployerMainInfo},
  data() {
    return {
      loaded: false,
      employer: {},
    };
  },
  watch: {
    '$route'() {
      this.getEmployer();
    },
  },
  mounted() {
    this.getEmployer();
  },
  methods: {
    updateBreadcrumbs() {
      this.$route.meta.breadcrumb = this.$route.meta.breadcrumb.filter(o => o.type !== 'empl');
      this.$route.meta.breadcrumb.push(
          {
            type: 'empl',
            name: this.employer.name,
          });
    },
    getEmployer() {
      this.loaded = false;
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/employer/${this.$route.params.id}`).
          then(({data}) => {
            this.employer = data.data;
            this.updateBreadcrumbs();
          }).
          finally(() => {
            this.loaded = true;
          });
    },
  },
};
</script>

<style scoped lang="scss">
</style>
