<template>
  <section class="page__section">
    <h2 class="institution-page__title title">
      Журнал
    </h2>

    <div class="filter_panel">
      <JournalFilter :selected-filter-type.sync="filterType" without-events/>
    </div>

    <EventsCatalog :array="journal"/>
  </section>
</template>

<script>
export default {
  name: 'EmployerJournal',
  props: {
    employer: Object,
  },
  data() {
    return {
      filterType: 0,
      journal: [],
      queryParams: {
        order_by: '-published_at',
        page: 1,
        per_page: 3,
      },
    };
  },
  mounted() {
    this.getJournalItems();
  },
  watch: {
    filterType: function(val) {
      if (val) {
        this.queryParams.type = val;
        this.getJournalItems();
      } else {
        delete this.queryParams.type;
        this.getJournalItems();
      }
    },
  },
  methods: {
    getJournalItems: function() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal`, {
        params: {...this.queryParams, employer_id: this.employer.id},
      }).then((response) => {
        if (response.status === 200) {
          const {data: {data: data}} = response;

          this.journal = data;
        }
      });
    },
  },
};
</script>

<style scoped>

</style>