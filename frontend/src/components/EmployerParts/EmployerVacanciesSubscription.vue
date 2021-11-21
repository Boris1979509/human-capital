<template>
  <div v-if="$loginStatus && userCanSubscribe">
    <Button class="btn--light" v-if="isSubscribed" @click.native="unsubscribe">Вы подписаны на вакансии</Button>
    <Button class="btn--light" v-else @click.native="subscribe">Подписаться на вакансии</Button>
  </div>
</template>
<script>
export default {
  name: 'EmployerVacanciesSubscription',
  props: {
    employer: Object,
  },
  data() {
    return {
      isSubscribed: false,
      queryParams: {
        type: 2,
      },
    };
  },
  mounted() {
    this.isSubscribed = this.employer.subscriptions?.vacancies || false;
  },
  computed: {
    subscriptionEndpoint() {
      return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/employer/${this.employer.id}/subscription`;
    },
    userCanSubscribe() {
      return this.$user?.type === 1;
    },
  },
  methods: {
    subscribe() {
      this.$http.post(this.subscriptionEndpoint, this.queryParams).then(() => {
        this.isSubscribed = true;
      });
    },
    unsubscribe() {
      this.$http.delete(this.subscriptionEndpoint, {params: this.queryParams}).then(() => {
        this.isSubscribed = false;
      });
    },
  },
};
</script>
