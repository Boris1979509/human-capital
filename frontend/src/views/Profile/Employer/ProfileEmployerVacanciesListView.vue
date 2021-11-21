<template>
  <ProfileEmployerWrapper>
    <VacanciesList/>
  </ProfileEmployerWrapper>
</template>

<script>
import VacanciesList from '@/components/EmployerProfile/VacanciesList';

export default {
  name: 'ProfileEmployerVacanciesListView',

  components: {
    VacanciesList,
  },
};
</script>
