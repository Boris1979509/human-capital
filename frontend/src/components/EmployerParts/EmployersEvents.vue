<template>
  <section class="page__section" style="margin-top: 72px;">
    <router-link to="/journal">
      <h1 class="title">
        Мероприятия работодателей
      </h1>
    </router-link>

    <div class="page__description">
      Погрузитесь в рабочую атмосферу во время дня без турникетов или<br/>подберите ярмарку вакансий подходящей отрасли
    </div>

    <div class="row" v-if="events.length > 0">
      <div class="col-33" v-for="item in events" :key="item.id">
        <NewsCard :image="item.cover !== null ? item.cover.url : null"
                  :id="item.id"
                  :title="item.title"
                  :date="item.published_at"
                  :type="item.type.id"/>
      </div>
    </div>

    <NothingFound text="Ничего не найдено" v-else/>
  </section>
</template>

<script>
export default {
  name: 'EmployersEvents',
  data() {
    return {
      events: [],
    };
  },
  mounted() {
    this.getEvents();
  },
  methods: {
    getEvents() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal`, {
        params: {
          type: 3,
          from_employers: 1,
          limit: 6,
        },
      }).then(res => {
        this.events = res.data.data;
      });
    },
  },
};
</script>

<style scoped>
.col-33{
  margin-bottom: 32px;
}
</style>