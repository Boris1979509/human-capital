<template>
  <a @click="$router.push(`/journal/${contentRoute}/${id}`)" class="news-card">
    <div class="news-card__img">
      <img :src="thumb" :alt="title"/>
      <FavoriteCardIndicator v-if="isFavorited" type="journal" :item-id="id"
                             @removedFromFavorites="$emit('removedFromFavorites', $event)"/>
    </div>
    <h5 class="news-card__title title" v-line-clamp="2" style="word-break: normal;">
      {{ title }}
    </h5>

    <div class="news-card__date">
      {{ makeDate(date) }}
    </div>
  </a>
</template>

<script>
import FavoriteCardIndicator from '@/components/UserFavorites/FavoriteCardIndicator';

const testImg = require('@/assets/svg/no_foto.svg');

export default {
  components: {FavoriteCardIndicator},

  name: 'NewsCard',

  props: {
    id: {
      type: [String, Number],
    },
    image: {
      type: String,
      default: () => '',
    },
    title: {
      type: String,
    },
    date: {
      type: String,
    },
    type: {
      type: Number,
    },
    isFavorited: {
      type: Boolean,
      default: false,
    },
  },
  data: function() {
    return {
      test: testImg,
      months: [
        'Января', 'Февраля', 'Марта',
        'Апреля', 'Мая', 'Июня',
        'Июля', 'Августа', 'Сентября',
        'Октября', 'Ноября', 'Декабря',
      ],
      routes: {
        [process.env.VUE_APP_JOURNAL_NEWS_TYPE]: 'news',
        [process.env.VUE_APP_JOURNAL_ARTICLE_TYPE]: 'article',
        [process.env.VUE_APP_JOURNAL_EVENT_TYPE]: 'event',
      },
    };
  },
  computed: {
    contentRoute() {
      return this.routes[this.type];
    },
    thumb() {
      return this.image || testImg;
    },
  },
  methods: {
    makeDate: function(date) {
      const day = this.$moment(date, 'YYYY/MM/DD').format('D');
      const month = this.$moment(date, 'YYYY/MM/DD').format('M');
      const completeDate = `${day} ${this.months[month - 1]}`;

      return completeDate;
    },
  },
};
</script>

<style>
.news-card__img {
  position: relative;
}

.news-card {
  cursor: pointer;
}
</style>
