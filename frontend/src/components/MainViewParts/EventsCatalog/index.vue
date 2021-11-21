<template>
  <div class="row">
    <div class="col-33" v-for="item in currentArr" :key="item.id">
      <NewsCard :image="item.cover !== null ? item.cover.url : null"
                :id="item.id"
                :title="item.title"
                :date="item.published_at"
                :type="item.type.id" />
    </div>

    <div class="col-100" v-if="currentArr.length === 0">
      <NothingFound text="По этому критерию записей не найдено" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'EventsCatalog',

  props: {
    array: {
      type: [Array, Object],
    },
    filterType: {
      type: String,
    },
  },

  watch: {
    array: function(val) {
      this.currentArr = val;
    }
  },

  mounted() {
    if (!this.array) {
      this.$store.dispatch('GET_EVENTS_PART_FROM_SERVER', {
        clear: true,
        params: {
          type: process.env.VUE_APP_JOURNAL_EVENT_TYPE,
          order_by: 'date_start',
          limit: 3,
          filter: this.filterType ? this.filterType : '',
        },
      }).then(() => {
        this.currentArr = this.events;
      });
    } else {
      this.currentArr = this.array;
    }
  },

  computed: {
    events: function() {
      return this.$events;
    },
  },

  data: function() {
    return {
      currentArr: [],
    }
  },
}
</script>
