<template>
  <ProfileUserWrapper>
    <EventCalendar calendar-type="user" v-if="isComponentLoaded" />

    <Loading v-else />
  </ProfileUserWrapper>
</template>

<script>
import EventCalendar from '@/components/EventCalendar.vue';

export default {
  name: 'ProfileUserCalendarView',

  components: {
    EventCalendar,
  },

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
