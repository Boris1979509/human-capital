<template>
  <div v-if="news.length>0">
    <div class="title">
      <slot name="title"></slot>
    </div>
    <div>
      <div class="row">
        <div class="col-50 content" v-for="(item, key) in news" :key="key">
          <NewsCard
              :id="item.id"
              :image="item.cover !== null ? item.cover.url : null"
              :title="item.title"
              :date="item.published_at"
              :type="item.type.id"
              :isFavorited="true"
              @removedFromFavorites="remove"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserFavoriteContent',
  props: {
    contentType: String,
  },
  data() {
    return {
      news: [],
    };
  },
  mounted() {
    this.getFavoriteNews();
  },
  methods: {
    getFavoriteNews() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal`,
          {
            params: {
              favorited: 1,
              type: this.contentType,
            },
          }).
          then(res => {
            this.news = res.data.data;
          });
    },
    remove(id) {
      this.news = this.news.filter(item => item.id !== id);
    },
  },
};
</script>

<style scoped>
.title {
  font-style: normal;
  font-weight: 800;
  font-size: 24px;
  line-height: 28px;
  color: #04153E;
  margin-bottom: 24px;
}

.content {
  margin-bottom: 25px;
}
</style>