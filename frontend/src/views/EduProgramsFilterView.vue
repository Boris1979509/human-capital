<template>
  <div class="inner-page">
    <Breadcrumbs :margin="24"/>

    <CardsFilter v-if="isComponentLoaded" :route-transfer="this.$route.params" />

    <Loading v-else />
  </div>
</template>

<script>
export default {
  name: 'EduProgramsFilterView',

  computed: {
    max_cost: function() {
      return this.$maxProgramCost;
    },
    filteredPrograms: function() {
      return this.$filteredPrograms;
    },
  },

  mounted() {
    this.isComponentLoaded = false;

    if (this.max_cost === null) {
      this.$store.dispatch('GET_MAX_PROGRAM_COST_FROM_SERVER', {clear: true}).then(() => {
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
