<template>
  <div class="wrapper_article_item">
    <div class="wrapper_article_item-grid">
      <main>
        <div class="title">
          <div class="title_ribbon">
            {{ article.type && article.type.name }}
          </div>
          <h1>{{ article.title }}</h1>
          <div class="title_date">
            {{ article.date | date }}
          </div>
        </div>
        <div class="article_item_content">
          <div
              v-if="images && images.length"
              class="article_item_content-slider"
          >
            <PhotoSlider :array="images"/>
          </div>
          <div class="article_item_content-text" v-html="article.text"></div>
        </div>
      </main>
      <aside>
        <Favorite
            v-if="article.id"
            :initialFavorited="article.is_favorited"
            type="journal"
            :itemId="article.id"
        />

        <SocialSharing
            v-if="article.id"
            :link="`https://hcap.d.rusatom.dev/journal/article/${$route.params.id}`"
            :title="article.title"
            description="Откройте для себя образовательные возможности региона"
            :image="images[0]"
            :label="true"
        />
        <div class="banner">
          <img src="@/assets/img/banner_example.png" alt=""/>
        </div>
      </aside>
    </div>
    <div class="related_items" v-if="related_article.length">
      <div class="title">
        <h2>Читайте также</h2>
      </div>
      <RelatedSlider
          :array="related_article"
          :route-name="'MainViewJournalarticle'"
      />
    </div>
  </div>
</template>

<script>
import Favorite from '../components/Favorite';

const testImg = require('@/assets/svg/no_foto.svg');

export default {
  name: 'MainViewJournalarticle',
  components: {Favorite},
  data() {
    return {
      testImg,
      article: {},
      related_article: [],
    };
  },
  watch: {
    $route() {
      this.getarticleDetails();
    },
  },
  created() {
    this.getarticleDetails();
    this.getRelatedArticle();
  },
  computed: {
    images() {
      let images = [];
      if (this.article.cover) {
        images.push(this.article.cover);
      }
      images.push(...this.article.images);
      return images;
    },
  },
  methods: {
    getRelatedArticle() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}/similar/?order_by=-published_at`,
      ).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal article item getting');
        const {
          data: {data: data},
        } = response;
        this.related_article = data;
      }).catch((err) => {
        console.log(err);
      });
    },
    getarticleDetails() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
      ).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal article item getting');
        const {
          data: {data: data},
        } = response;
        this.article = {...data};
      }).catch((err) => {
        console.log(err);
      });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/scss/utilities/_mixins.scss';

.wrapper_article_item {
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
          background: #bb9af4;
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

      .article_item_content {
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
