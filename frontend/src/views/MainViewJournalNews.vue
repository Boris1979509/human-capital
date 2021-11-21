<template>
  <div class="wrapper_news_item">
    <meta property="og:image"
          content="https://avatars.mds.yandex.net/get-zen_doc/1708203/pub_5d985db0aad43600b1cdc12a_5d985e195eb26800ad09533f/scale_1200">
    <meta property="og:image:width" content="968">
    <meta property="og:image:height" content="504">
    <div class="wrapper_news_item-grid">
      <main>
        <div class="title">
          <div class="title_ribbon">
            {{ news.type && news.type.name }}
          </div>
          <h1>{{ news.title }}</h1>
          <div class="title_date">
            {{ news.date | date }}
          </div>
        </div>
        <div class="news_item_content">
          <div
              v-if="images && images.length"
              class="news_item_content-slider"
          >
            <PhotoSlider :array="images"/>
          </div>
          <ModalGallery :array="images" :bodyWidth="'646'" :bodyHeight="'671'"/>
          <div class="news_item_content-text" v-html="news.text"></div>
        </div>
      </main>
      <aside>
        <Favorite
            v-if="news.id"
            :initialFavorited="news.is_favorited"
            type="journal"
            :itemId="news.id"
        />
        <SocialSharing
            v-if="news.id"
            :link="`https://hcap.d.rusatom.dev/journal/news/${$route.params.id}`"
            :title="news.title"
            description="Откройте для себя образовательные возможности региона"
            :image="images[0]"
            :label="true"
        />
        <div class="banner">
          <img src="@/assets/img/banner_example.png" alt="">
        </div>
      </aside>
    </div>
    <div class="related_items">
      <div class="title">
        <h2>Читайте также</h2>
      </div>
      <RelatedSlider :array="relatedNews" :route-name="'MainViewJournalNews'"/>
    </div>
  </div>
</template>

<script>
import Favorite from '../components/Favorite';

const testImg = require('@/assets/svg/no_foto.svg');

export default {
  name: 'MainViewJournalNews',
  components: {Favorite},
  data() {
    return {
      testImg,
      news: {},
      relatedNews: [],
    };
  },
  computed: {
    images() {
      let images = [];
      if (this.news.cover) {
        images.push(this.news.cover);
      }
      images.push(...this.news.images);
      return images;
    },
  },
  watch: {
    $route() {
      this.getNewsDetails();
    },
  },
  created() {
    this.getNewsDetails();
    this.getRelatedNews();
  },
  methods: {
    getRelatedNews() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}/similar/?order_by=-published_at`,
      ).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal news item getting');
        const {
          data: {data: data},
        } = response;
        this.relatedNews = data;
      }).catch((err) => {
        console.log(err);
      });
    },
    getNewsDetails() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
      ).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal news item getting');
        const {
          data: {data: data},
        } = response;
        this.news = {...data};
      }).catch((err) => {
        console.log(err);
      });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/scss/utilities/_mixins.scss';

.wrapper_news_item {
  &-grid {
    display: grid;
    grid-template-columns: 8fr 4fr;
    grid-gap: 32px;
    margin-bottom: 104px;

    main {
      overflow: hidden;

      .title {
        margin-bottom: 32px;

        &_ribbon {
          font-family: Golos;
          font-size: 13px;
          font-style: normal;
          font-weight: 500;
          line-height: 16px;
          background: #81abee;
          display: inline-block;
          padding: 4px 12px;
          color: var(--light-color);
          border-radius: 16px;
          margin-bottom: 8px;
        }

        h1 {
          font-family: Golos;
          font-size: 40px;
          font-style: normal;
          font-weight: 800;
          line-height: 44px;
          color: var(--main-color-dark);
          letter-spacing: 0em;
          text-align: left;
          margin-bottom: 12px;
        }

        &_date {
          color: var(--main-color-dark);
          font-family: Golos;
          font-style: normal;
          font-weight: normal;
          font-size: 16px;
          line-height: 20px;
        }
      }

      .news_item_content {
        &-text {
          word-break: break-all;

          /deep/ img {
            max-width: 100% !important;
          }
        }

        &-slider {
          margin-bottom: 32px;
        }
      }
    }

    aside {
      .social {
        margin-top: 0;
        margin-bottom: 40px;

        &__label {
          color: var(--main-color-dark-trans-middle);
          font-family: Golos;
          font-style: normal;
          font-weight: normal;
          font-size: 13px;
          line-height: 16px;
          margin-bottom: 12px;
        }
      }
    }
  }

  .related_items {
    .title {
      margin-bottom: 24px;

      h2 {
        font-family: Golos;
        font-size: 32px;
        font-style: normal;
        font-weight: 800;
        line-height: 36px;
        letter-spacing: 0em;
        text-align: left;
      }
    }

    /deep/ .item_label {
      @include multiLineEllipsis($lineHeight: 20px, $lineCount: 3);
    }
  }
}
</style>
