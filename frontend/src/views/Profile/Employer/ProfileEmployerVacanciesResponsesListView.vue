<template>
  <ProfileEmployerWrapper>
    <VacanciesResponsesList/>
  </ProfileEmployerWrapper>
</template>

<script>
import VacanciesResponsesList from '@/components/EmployerProfile/VacanciesResponsesList';

export default {
  name: 'ProfileEmployerVacanciesResponsesListView',

  components: {
    VacanciesResponsesList,
  },
}
</script>
