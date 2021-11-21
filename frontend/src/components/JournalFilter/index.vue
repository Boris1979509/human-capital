<template>
  <div class="filter_panel">
    <div
        v-for="(item, index) in filter_items"
        class="item"
        :class="{ selected: item.selected, disabled: item.disabled }"
        @click="!item.disabled ? setSelectedFilterItem(filter_items, index) : ''"
        :key="index"
    >
      {{ item.label }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'JournalFilter',
  props: {
    withoutEvents: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      queryParams: {
        order_by: '-published_at',
        page: 1,
        per_page: 1,
      },
      filter_items: [
        {
          label: 'Все материалы',
          type: 0,
          selected: true,
        },
        {
          label: 'Новости',
          type: process.env.VUE_APP_JOURNAL_NEWS_TYPE,
          selected: false,
          disabled: false,
        },
        {
          label: 'Статьи',
          type: process.env.VUE_APP_JOURNAL_ARTICLE_TYPE,
          selected: false,
          disabled: false,
        },
      ],
    };
  },
  async created() {
    if (!this.withoutEvents) {
      this.filter_items.push({
        label: 'Мероприятия',
        type: process.env.VUE_APP_JOURNAL_EVENT_TYPE,
        selected: false,
        disabled: false,
      });
    }

    const ttt = this.filter_items[Symbol.iterator]();

    for await (let value of ttt) {
      // (4)
      if (value.type) {
        this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/`, {
          params: {...this.queryParams, type: value.type},
        }).then((response) => {
          if (response.status === 200) {
            const {
              data: {data: data},
            } = response;
            this.filter_items.forEach((item) => {
              if (item.type === value.type) item.disabled = !data.length;
            });
          }
        });
      }
    }
  },
  methods: {
    setSelectedFilterItem(array, index) {
      if (this.isDisabled) this.isDisabled = false;
      if (array.length) {
        array.forEach((element) => {
          if (element.selected) element.selected = false;
        });
      }
      if (array[index]) {
        array[index].selected = true;
        this.$emit('update:selectedFilterType', +array[index].type);
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.filter_panel {
  display: flex;
  margin-bottom: 32px;

  .item {
    font-family: Golos;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 20px;
    letter-spacing: 0em;
    text-align: left;
    margin-left: 20px;
    cursor: pointer;

    &.disabled {
      opacity: 0.32;
      cursor: auto;
    }

    &:first-child {
      margin-left: 0;
    }

    &.selected:after {
      content: '';
      background: var(--main-color);
      display: block;
      height: 4px;
      border-radius: 2px;
      margin-top: 8px;
    }
  }
}
</style>
