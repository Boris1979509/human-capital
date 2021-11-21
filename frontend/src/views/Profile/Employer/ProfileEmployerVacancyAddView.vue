<template>
  <ProfileEmployerWrapper>
      <EmployerAddVacancyForm :id="id"/>
  </ProfileEmployerWrapper>
</template>

<script>
import EmployerAddVacancyForm from '@/components/EmployerProfile/EmployerAddVacancyForm';

export default {
  name: 'ProfileEmployerVacancyAddView',

  props:{
    id: [String, Number]
  },

  components: {
    EmployerAddVacancyForm
  },
}
</script>
