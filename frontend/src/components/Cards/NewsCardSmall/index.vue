<template>
  <router-link :to="`journal/${contentRoute}/${id}`" class="news-card news-card--small">
    <div class="news-card__img">
      <img :src="image || test" :alt="title" v-if="image || test">
    </div>

    <div class="news-card__container">
      <h5 class="news-card__title title" v-line-clamp="2" style="word-break: normal;">
        {{ title }}
      </h5>

      <div class="news-card__date">
        {{ makeDate(date) }}
      </div>
    </div>
  </router-link>
</template>

<script>
const testImg = require('@/assets/svg/no_foto.svg');

export default {
  name: 'NewsCardSmall',

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
    }
  },
  computed: {
    contentRoute() {
      return this.routes[this.type];
    },
  },
  methods: {
    makeDate: function(date) {
      const day = this.$moment(date, 'YYYY/MM/DD').format('D');
      const month = this.$moment(date, 'YYYY/MM/DD').format('M');
      const completeDate = `${day} ${this.months[month - 1]}`;

      return completeDate;
    }
  },
}
</script>
