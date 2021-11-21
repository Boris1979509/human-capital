<template>
  <ProfileOrgWrapper>
    <ProgramList v-if="isComponentLoaded" />

    <Loading v-else />
  </ProfileOrgWrapper>
</template>

<script>
export default {
  name: 'ProfileOrgProgramsView',

  computed: {
    orgInfo: function() {
      return this.$organization;
    },
    programs: function() {
      return this.$programs;
    },
  },

  mounted() {
    this.isComponentLoaded = false;

    //if (this.orgInfo.length === 0 || this.programs.length === 0) {
      this.$store.dispatch('GET_ORG_DATA_FROM_SERVER').then(() => {
        this.$store.dispatch('GET_PROGRAMS_LIST_FROM_SERVER', {
          clear: true,
          params: {
            institution: this.orgInfo[0].id,
          }
        }).then(() => {
          this.isComponentLoaded = true;
        });
      });
    // } else {
    //   this.isComponentLoaded = true;
    // }
  },

  data: function() {
    return {
      isComponentLoaded: false,
    }
  },
}
</script>
