<template>
  <div class="compilation__wrapper">
    <main>
      <breadcrumbs class="mb-26"/>
      <div class="title">
        <h1>
          {{ compilationDescription.title }}
        </h1>
        <div class="info">
          <div class="reading-time">
            <div class="reading-time__label">Время чтения</div>
            <div class="reading-time__value">{{ compilationDescription.reading_time }}</div>
          </div>
          <div class="publication-date">
            <div class="publication-date__label">Дата публикации</div>
            <div class="publication-date__value">
              {{ compilationDescription.published_at | date }}
            </div>
          </div>
        </div>
      </div>
      <div class="compilation-cover">
        <img
            :src="
            compilationDescription.cover && compilationDescription.cover.url
          "
            alt=""
        />
      </div>
      <div class="description">
        {{ compilationDescription.description }}
      </div>
      <div class="compilation-parts-wrapper">
        <div
            class="compilation-part"
            v-for="item in compilation"
            :key="item.id"
        >
          <component
              v-bind:is="`${item.selectionable_type}Component`"
              :info="item"
          ></component>
          <div class="compilation-part__cover">
            <img :src="item.cover && item.cover.url"/>
          </div>
          <div class="compilation-part__description">
            {{ item.description }}
          </div>
        </div>
      </div>
    </main>
    <aside>
      <Favorite
          v-if="compilationDescription.id"
          :initialFavorited="compilationDescription.is_favorited"
          type="selection"
          :itemId="compilationDescription.id"
      />
      <!-- <div class="social">
        <div class="social__label">Поделиться в соцсетях</div>
        <a href="#" title="ВКонтакте" class="social__link">
          <Icon xlink="vk" viewport="0 0 32 32" />
        </a>

        <a href="#" title="Инстаграм" class="social__link">
          <Icon xlink="instagram" viewport="0 0 32 32" />
        </a>

        <a href="#" title="Фейсбук" class="social__link">
          <Icon xlink="fb" viewport="0 0 32 32" />
        </a>
      </div> -->
      <SocialSharing
          :link="`https://hcap.d.rusatom.dev/selections/${$route.params.id}`"
          :title="compilationDescription.title"
          :image="compilationDescription.cover"
          :description="compilationDescription.description"
          :label="true"
      />

      <div class="banner">
        <img src="@/assets/img/banner_example.png" alt=""/>
      </div>
    </aside>
  </div>
</template>

<script>
import Favorite from '../components/Favorite';

export default {
  name: 'MainViewCompilationsItem',
  components: {Favorite},
  // components: {
  //   'Institution': () => import('@/components/Selections/InstitutionComponent'),
  // },
  data() {
    return {
      compilation: {},
      compilationDescription: {},
    };
  },
  created() {
    this.getCompilationDescription();
    this.getCompilationItem();
  },
  methods: {
    getCompilationDescription() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/selections/${this.$route.params.id}`,
          {
            params: {
              order_by: '-published_at',
            },
          },
      ).then((response) => {
        if (response.status === 200) {
          const {
            data: {data: data},
          } = response;
          this.compilationDescription = data;
        } else throw Error('error occured while compilation item getting');
      }).catch((err) => {
        console.log(err);
      });
    },
    getCompilationItem() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/selections/${this.$route.params.id}/items`,
          {
            params: {
              order_by: '-published_at',
            },
          },
      ).then((response) => {
        const {
          data: {data: data},
        } = response;
        this.compilation = data;
        // this.compilation = data.reduce((acc, item) => {
        //   acc[item.selectionable_type] = item;
        //   return acc;
        // }, {});
      }).catch((err) => {
        console.log(err);
      });
    },
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/scss/utilities/_mixins.scss';

.compilation {
  $c: &;

  &__wrapper {
    //util styles
    .mt-24 {
      margin-top: 24px;
    }

    .mb-26 {
      margin-bottom: 26px;
    }

    //end util styles
    main {
      max-width: 800px;

      .title {
        margin-bottom: 32px;

        h1 {
          font-size: 40px;
          font-style: normal;
          font-weight: 800;
          line-height: 44px;
          margin-bottom: 15px;

          &:first-letter {
            text-transform: uppercase;
          }
        }

        .info {
          font-size: 16px;
          font-style: normal;
          font-weight: 400;
          line-height: 20px;
          display: flex;

          .reading-time {
            display: flex;
            margin-right: 32px;

            &__label {
              color: var(--main-color);
              margin-right: 8px;
            }

            &__value {
              opacity: 0.64;
            }
          }

          .publication-date {
            display: flex;

            &__label {
              color: var(--main-color);
              margin-right: 8px;
            }

            &__value {
              opacity: 0.64;
            }
          }
        }
      }

      .compilation-cover {
        margin-bottom: 16px;

        img {
          max-width: 100%;
        }
      }

      .description {
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        margin-bottom: 40px;
      }

      .compilation-parts-wrapper {
        .compilation-part {
          margin-bottom: 40px;

          &__cover {
            margin-top: 16px;
          }

          &__description {
            margin-top: 16px;
            opacity: 0.72;
          }
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

    width: 100%;
    display: grid;
    grid-template-columns: 8fr 4fr;
    grid-gap: 32px;

    #{$c}__item {
      $e: &;
      // display: grid;
      // grid-template-columns: 8fr 4fr;
      // grid-gap: 58px;
      .item {
        &__title {
          margin-bottom: 16px;
          @include multiLineEllipsis($lineHeight: 28px, $lineCount: 2);

          &__text {
            font-family: Golos;
            font-size: 24px;
            font-style: normal;
            font-weight: 800;
            line-height: 28px;
            position: relative;
            width: fit-content;
            padding-right: 16px;
            cursor: pointer;
          }

          img {
            margin-left: 8px;
          }
        }

        &__date {
          font-family: Golos;
          font-size: 16px;
          font-style: normal;
          font-weight: 400;
          line-height: 20px;
          opacity: 0.72;

          &.event {
            font-weight: 800;
            opacity: 1;
            color: var(--main-color);
            margin-bottom: 16px;
          }
        }

        &__contacts {
          display: flex;
          margin-bottom: 24px;

          svg {
            margin-right: 10px;
          }

          &__address,
          &__phone {
            font-family: Golos;
            font-size: 16px;
            font-style: normal;
            font-weight: 500;
            line-height: 20px;
            display: flex;
            align-items: center;
            margin-right: 26px;
            opacity: 0.72;
          }
        }

        &__viewers {
          display: flex;
          margin-bottom: 40px;

          .item__target__auditory {
            &_label {
              font-size: 13px;
              font-style: normal;
              font-weight: 400;
              line-height: 16px;
              margin-bottom: 4px;
              margin-right: 32px;
            }

            &_value {
              font-size: 16px;
              font-style: normal;
              font-weight: 800;
              line-height: 20px;
              color: var(--main-color);
            }
          }

          .item__target__age {
            &__label {
              font-size: 13px;
              font-style: normal;
              font-weight: 400;
              line-height: 16px;
              margin-bottom: 4px;
            }

            &__value {
              font-size: 16px;
              font-style: normal;
              font-weight: 800;
              line-height: 20px;
              color: var(--main-color);
            }
          }
        }

        &__theme {
          &__label {
            font-size: 13px;
            font-style: normal;
            font-weight: 400;
            line-height: 16px;
            margin-bottom: 4px;
          }

          &__value {
            display: flex;

            &__item {
              font-size: 13px;
              font-style: normal;
              font-weight: 500;
              line-height: 16px;
              background: #81abee;
              color: var(--light-color);
              padding: 4px 12px;
              border-radius: 16px;
              margin-right: 8px;
            }
          }
        }

        &__description {
          font-size: 13px;
          font-style: normal;
          font-weight: 400;
          line-height: 16px;
          margin-bottom: 24px;

          &__row {
            $r: &;
            // display: grid;
            // grid-template-columns: repeat(4, 1fr);
            // grid-gap: 32px;
            &.value {
              .item__description__row__item {
                font-size: 16px;
                font-style: normal;
                font-weight: 800;
                line-height: 20px;
                color: var(--main-color);
              }
            }

            &.label {
              .item__description__row__item {
                font-size: 13px;
                font-style: normal;
                font-weight: 400;
                line-height: 16px;
                margin-bottom: 4px;
              }
            }
          }
        }

        &__label {
          color: var(--main-color);
          font-family: Golos;
          font-size: 16px;
          font-style: normal;
          font-weight: 800;
          line-height: 20px;
        }
      }
    }
  }
}
</style>
