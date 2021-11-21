<template>
  <ProfileEmployerWrapper>
    <EventCalendar calendar-type="employer" v-if="isComponentLoaded" />

    <Loading v-else />
  </ProfileEmployerWrapper>
</template>

<script>
import EventCalendar from '@/components/EventCalendar.vue';

export default {
  name: 'ProfileEmployerCalendarView',

  components: {
    EventCalendar,
  },

  computed: {
    employerInfo: function() {
      return this.$employer;
    },
  },

  mounted() {
    this.isComponentLoaded = false;

    if (!this.employerInfo && Object.keys(this.employerInfo).length !== 0 && this.employerInfo.constructor === Object) {
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
