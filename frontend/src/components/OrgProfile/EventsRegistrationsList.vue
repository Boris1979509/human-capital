<template>
  <div class="profile__container">
    <h4 class="title">Заявки на участие в мероприятиях</h4>
    <Loading v-if="isLoading"/>
    <table class="resp-tab" v-else-if="events.length > 0">
      <thead>
      <tr>
        <th>Мероприятие</th>
        <th>Статус мероприятия</th>
        <th>Окончание приема заявок</th>
        <th>Обработано заявок</th>
        <th>Вскго заявок</th>
      </tr>
      </thead>

      <tbody>
      <EventRegistrationRow v-for="event in events" :key="event.id" :event="event" :type="type"/>
      </tbody>
    </table>

    <NothingFound v-else text="Не найдено"/>


  </div>
</template>

<script>
import EventRegistrationRow from '@/components/OrgProfile/EventRegistrationRow';

export default {
  name: 'EventsRegistrationsList',
  components: {EventRegistrationRow},
  data: function() {
    return {
      isLoading: true,
      events: [],
      organization: null,
      employer: null,
    };
  },
  computed:{
    type(){
      if (this.organization){
        return 'institution'
      }
      if(this.employer){
        return 'employer'
      }
      return null
    },
  },
  watch: {
    $organization() {
      this.organization = this.$organization[0];
      this.getEvents();
    },
    $employer() {
      this.employer = this.$employer;
      this.getEvents();
    },
  },

  mounted() {
    console.log('mount');
    if (this.$organization[0]) {
      this.organization = this.$organization[0];
    }
    if (this.$employer) {
      this.employer = this.$employer;
    }
    this.getEvents();
  },

  methods: {
    getEvents() {
      if (!this.organization && !this.employer) {
        return;
      }
      this.isLoading = true;
      let url = null;
      if (this.employer) {
        url = `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/${this.employer.id}/journal`;
      }
      if (this.organization) {
        url = `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.organization.id}/journal`;
      }
      this.$http.get(
          url,
          {
            params: {
              type: 3,
            },
          },
      ).
          then(res => {
            this.events = res.data.data;
          }).finally(() => {
        this.isLoading = false;
      });
    },
  },
};
</script>

<style scoped>

</style>