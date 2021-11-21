<template>
  <ProfileOrgWrapper>
    <EventCalendar calendar-type="institution" :institution-id="orgInfo[0].id" v-if="isComponentLoaded" />

    <Loading v-else />
  </ProfileOrgWrapper>
</template>

<script>
import EventCalendar from '@/components/EventCalendar.vue';

export default {
  name: 'ProfileOrgCalendarView',

  components: {
    EventCalendar,
  },

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
  },

  data: function() {
    return {
      isComponentLoaded: false,
    }
  },
}
</script>
