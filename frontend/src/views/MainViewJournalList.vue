<template>
  <div>
    <div class="title">
      <h1>Журнал</h1>
      <div class="title_description">
        В нашем журнале собраны актуальные новости,интересные статьи<br />
        и анонсы грядущих мероприятий
      </div>
    </div>
    <!-- <div class="filter_panel">
      <div
        v-for="(item, index) in filter_items"
        class="item"
        :class="{ selected: item.selected }"
        @click="setSelectedFilterItem(filter_items, index)"
        :key="index"
      >
        {{ item.label }}
      </div>
    </div> -->
    <JournalFilter :selected-filter-type.sync="filterType" />
    <div class="wrapper_journal_items">
      <div
        class="item"
        @click="setItemRoute(item)"
        v-for="(item, idx) in journal"
        :key="idx"
      >
        <div
          class="item_img"
          :style="
            'background-image: url(' +
              ((item.cover && item.cover.url) || testImg) +
              ')'
          "
        >
          <div
            :class="{ item_ribbon: true, [itemRibbons[item.type.id]]: true }"
          >
            {{ item.type && item.type.name }}
          </div>
        </div>
        <div class="item_label">
          {{ item.title }}
        </div>
        <div class="item_date">
          {{ item.published_at | date }}
        </div>
      </div>
    </div>
    <Button
      @click.native="getJournalItems(true)"
      :disabled="isDisabled"
      v-if="!this.hideLoadMoreBtn"
      class="btn--light mt-32px"
    >
      Показать ещё
    </Button>
  </div>
</template>

<script>
const testImg = require('@/assets/svg/no_foto.svg');

export default {
  name: 'MainViewJournalList',
  props: {
    items: {
      type: Array,
      default: () => [],
    },
  },
  data() {
    return {
      hideLoadMoreBtn: false,
      isDisabled: false,
      filterType: 0,
      queryParams: {
        order_by: '-published_at',
        page: 1,
        per_page: 15,
      },
      typeViews: {
        [process.env.VUE_APP_JOURNAL_NEWS_TYPE]: 'MainViewJournalNews',
        [process.env.VUE_APP_JOURNAL_ARTICLE_TYPE]: 'MainViewJournalArticle',
        [process.env.VUE_APP_JOURNAL_EVENT_TYPE]: 'MainViewJournalEvent',
      },
      testImg: testImg,
      itemRibbons: {
        [process.env.VUE_APP_JOURNAL_NEWS_TYPE]: 'news',
        [process.env.VUE_APP_JOURNAL_ARTICLE_TYPE]: 'article',
        [process.env.VUE_APP_JOURNAL_EVENT_TYPE]: 'event',
      },
      journal: [],
    };
  },
  created() {
    // all (published and not) events fetching
    this.getJournalItems();
  },
  methods: {
    getJournalItems(loadMore = false) {
      if (loadMore) this.queryParams.page++;
      this.$http
        .get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/`, {
          params: { ...this.queryParams },
        })
        .then((response) => {
          if (response.status === 200) console.log(response);
          else throw Error('error occured while journal all items getting');
          const {
            data: { data: data },
          } = response;
          if (!data.length) {
            this.isDisabled = true;
            return;
          }
          if (loadMore) {
            this.journal.push(...data);
          } else this.journal = data;
          response.data.links.next ? this.hideLoadMoreBtn = false : this.hideLoadMoreBtn = true;
        })
        .catch((err) => {
          console.log(err);
        });
    },
    setItemRoute(item) {
      if (item)
        this.$router.push({
          name: this.typeViews[item.type.id],
          params: {
            id: item.id,
          },
        });
    },
  },
  watch: {
    filterType: function(val) {
      if (this.isDisabled) this.isDisabled = false;
      this.queryParams.page = 1;
      if (val) {
        this.queryParams.type = val;
        this.getJournalItems();
      } else {
        delete this.queryParams.type;
        this.getJournalItems();
      }
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/scss/utilities/_mixins.scss';
.title {
  margin-bottom: 32px;
  h1 {
    font-family: Golos;
    font-size: 40px;
    font-style: normal;
    font-weight: 800;
    line-height: 44px;
    color: var(--main-color-dark);
    letter-spacing: 0em;
    text-align: left;
    margin-bottom: 8px;
  }
  &_description {
    font-family: Golos;
    font-size: 16px;
    font-style: normal;
    font-weight: 400;
    line-height: 24px;
    color: var(--main-color-dark);
    letter-spacing: 0em;
    text-align: left;
  }
}
.wrapper_journal_items {
  display: grid;
  grid-template-columns: repeat(3, 1fr);
  grid-gap: 32px;
  justify-items: center;
  .item {
    width: 293px;
    display: inline-block;
    cursor: pointer;
    &_img {
      width: 100%;
      height: 183px;
      background-size: cover;
      background-repeat: no-repeat;
      margin-bottom: 16px;
      border-radius: 16px;
      position: relative;
      overflow: hidden;
      .item_ribbon {
        &.news {
          background: #f196a5;
        }
        &.article {
          background: #bb9af4;
        }
        &.event {
          background: #8dc95e;
        }
      }
    }
    &_ribbon {
      position: absolute;
      font-family: Golos;
      font-style: normal;
      font-weight: normal;
      font-size: 13px;
      line-height: 16px;
      color: #ffffff;
      padding: 4px 12px;
      border-radius: 16px;
      top: 8px;
      left: 8px;
    }
    &_label {
      font-family: Golos;
      font-style: normal;
      font-weight: 800;
      font-size: 20px;
      line-height: 28px;
      color: #04153e;
      margin-bottom: 8px;
      @include multiLineEllipsis($lineHeight: 28px, $lineCount: 3);
    }
    &_date {
      font-family: Golos;
      font-style: normal;
      font-weight: normal;
      font-size: 16px;
      line-height: 20px;
      color: #04153e;
      opacity: 0.48;
    }
  }
}
.mt-32px {
  margin-top: 32px;
}
</style>
