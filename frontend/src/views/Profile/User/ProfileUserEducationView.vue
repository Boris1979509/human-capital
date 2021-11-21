<template>
  <ProfileUserWrapper>
    <EducationForm v-if="isComponentLoaded" />

    <Loading v-else />
  </ProfileUserWrapper>
</template>

<script>


export default {
  name: 'ProfileUserEducationView',

  computed: {
    personalInfo: function() {
      return this.$personal;
    },
  },

  mounted() {
    this.isComponentLoaded = false;

    if (this.personalInfo.length === 0) {
      this.$store.dispatch('GET_PERSONAL_DATA_FROM_SERVER').then(() => {
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
