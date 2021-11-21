<template>
  <div class="row">
    <div class="col-33" v-for="item in journal.slice(0, 2)" :key="item.id">
      <NewsCard :image="item.cover !== null ? item.cover.url : null"
                :id="item.id"
                :title="item.title"
                :date="item.published_at"
                :type="item.type.id" />
    </div>

    <div class="col-33">
      <NewsCardSmall v-for="item in journal.slice(2, 5)" :key="item.id"
                     :id="item.id"
                     :image="item.cover !== null ? item.cover.url : null"
                     :title="item.title"
                     :date="item.published_at"
                     :type="item.type.id" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'JournalCatalog',

  props: {
    filterType: String,
  },

  mounted() {
    this.$store.dispatch('GET_JOURNAL_PART_FROM_SERVER', {
      clear: true,
      params: {
        //type: process.env.VUE_APP_JOURNAL_ARTICLE_TYPE,
        order_by: '-published_at',
        limit: 5,
        filter: this.filterType ? this.filterType : '',
      },
    });
  },

  computed: {
    journal: function() {
      return this.$journalPart;
    },
  },
}
</script>
