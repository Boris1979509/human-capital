<template>
  <ProfileEmployerWrapper>
    <div v-if="isComponentLoaded">
      <EmployerInfoForm/>
    </div>

    <Loading v-else/>
  </ProfileEmployerWrapper>
</template>

<script>
import EmployerInfoForm from '@/components/EmployerProfile/EmployerInfoForm';

export default {
  name: 'ProfileEmployerInfoView',

  components: {
    EmployerInfoForm,
  },

  computed: {
    employerInfo: function() {
      return this.$employer;
    },
  },

  mounted() {
    this.isComponentLoaded = false;

    if (this.employerInfo.length === 0) {
      this.$store.dispatch('GET_EMPLOYER_DATA_FROM_SERVER').then(() => {
        this.isComponentLoaded = true;
      });
    } else {
      this.isComponentLoaded = true;
    }
  },

  data: function() {
    return {
      isComponentLoaded: false,
    }
  },
}
</script>
