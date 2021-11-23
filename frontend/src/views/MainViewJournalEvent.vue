<template>
  <div class="wrapper_news_item">
    <div class="wrapper_news_item-grid">
      <main>
        <div class="title">
          <div class="title_ribbon">
            {{ event.type && event.type.name }}
          </div>
          <h1>{{ event.title }}</h1>
          <div class="title_type">
            <!-- Экскурсия, оффлайн -->
          </div>
          <div class="title_date">
            <span class="title_date" v-if="event.date_start">{{ event.date_start | dateTime }}</span>
            <span class="title_date" v-if="event.date_end"> - {{ event.date_end | dateTime }}</span>
          </div>
          <div class="title_contacts">
            <div class="title_contacts-address" v-if="event.address">
              <Icon xlink="map_marker" viewport="0 0 12 16"/>
              {{ event.address }}
            </div>
            <div class="title_contacts-phone" v-if="event.phone">
              <Icon xlink="phone" viewport="0 0 16 16"/>
              {{ event.phone }}
            </div>
          </div>
          <div class="title_univer-avatar">
            <img
                :src="
                event.institution &&
                  event.institution.avatar &&
                  event.institution.avatar.thumb
              "
                alt=""
            />
          </div>
        </div>
        <div class="news_item_content">
          <div
              v-if="images && images.length"
              class="news_item_content-slider"
          >
            <PhotoSlider :array="images"/>
          </div>
          <div class="news_item_content-text" v-html="event.text"></div>
        </div>
        <div class="news_item_viewers">
          <div class="target_auditory">
            <div
                class="target_auditory_label"
                v-if="event && event.target_audience && event.target_audience.length"
            >
              Целевая аудитория
            </div>
            <div class="target_auditory_value">
              {{ getEventTargetAudience }}
            </div>
          </div>
          <div class="taget_age" v-if="event && event.participants_age && event.participants_age.length">
            <div class="taget_age_label">
              Возраст участников
            </div>
            <div class="taget_age_value">
              {{ getEventParticipantsAge }}
            </div>
          </div>
        </div>
        <div class="news_item_theme">
          <div class="theme_label" v-if="event.tags && event.tags.length">
            Тематика
          </div>
          <div class="theme_value">
            <div
                v-for="(item, idx) of event.tags"
                :key="idx"
                class="theme_value_item"
            >
              {{ item }}
            </div>
          </div>
        </div>
      </main>
      <aside>
        <Favorite
            v-if="event.id"
            :initialFavorited="event.is_favorited"
            type="journal"
            :itemId="event.id"
        />

        <RegisterToEvent
            v-if="event.id"
            :event="event"
        />

        <SocialSharing
            v-if="event.id"
            :link="`https://hcap.d.rusatom.dev/journal/event/${$route.params.id}`"
            :title="event.title"
            description="Откройте для себя образовательные возможности региона"
            :image="images[0]"
            :label="true"
        />

        <div class="banner">
          <img src="@/assets/img/banner_example.png" alt=""/>
        </div>
      </aside>
    </div>
    <div
        class="news_item_speakers"
        v-if="event.speakers && event.speakers.length"
    >
      <div class="news_item_speakers-label">
        Спикеры и эксперты
      </div>
      <div
          class="news_item_speakers-wrapper"
          :style="`grid-template-columns: repeat(${event.speakers.length}, 1fr)`"
      >
        <div v-for="(item, idx) of event.speakers" :key="idx" class="speaker">
          <div
              class="speaker_avatar"
              :style="`background: url(${item.avatar && item.avatar.thumb})`"
          ></div>
          <div class="speaker_name">{{ item.name }}</div>
          <div class="speaker_position">{{ item.position }}</div>
        </div>
      </div>
    </div>
    <div class="map" v-if="event.id">
      <Map :markers="[{
        coords: event.coords,
        title: event.title,
        description: event.address
      }]"/>
    </div>
    <div class="related_items">
      <div class="title">
        <h2>Другие мероприятия</h2>
      </div>
      <RelatedSlider
          :array="relatedEvent"
          :route-name="'MainViewJournalEvent'"
      />
    </div>
  </div>
</template>

<script>
import Favorite from '../components/Favorite';
import Map from '@/components/Map';
import RegisterToEvent from '@/components/RegisterToEvent';

const testImg = require('@/assets/svg/no_foto.svg');

export default {
  name: 'MainViewJournalEvent',

  components: {
    RegisterToEvent,
    Map,
    Favorite,
  },

  data() {
    return {
      testImg,
      event: {},
      relatedEvent: [],
    };
  },
  computed: {
    images() {
      let images = [];
      if (this.event.cover) {
        images.push(this.event.cover);
      }
      images.push(...this.event.images);
      return images;
    },
    getEventTargetAudience() {
      let audienceList = [];
      if (this.event?.target_audience?.length) {
        audienceList = this.event.target_audience.reduce((acc, item) => {
          const tmpItem = `${item.name}`;
          acc.push(tmpItem);
          return acc;
        }, []);
      }
      if (!audienceList.length) return '';
      else if (audienceList.length === this.dictionariesInst_auditory_journalList.length)
        return 'Все';
      else return audienceList.join(', ');
    },
    getEventParticipantsAge() {
      let ageList = [];
      if (this.event?.participants_age?.length) {
        ageList = this.event.participants_age.reduce((acc, item) => {
          const tmpItem =
              item.name.indexOf('+') !== -1 ? `${item.name}` : `${item.name} лет`;
          acc.push(tmpItem);
          return acc;
        }, []);
      }
      if (!ageList.length) return '';
      else if (ageList.length === this.dictionariesInst_ageList.length)
        return 'Все';
      else return ageList.join(', ');
    },
    dictionariesInst_auditory_journalList() {
      return this.$dictionaries.inst_auditory_journal;
    },
    dictionariesInst_ageList() {
      return this.$dictionaries.inst_age;
    },
    orgInfo: function() {
      return this.$organization;
    },
  },
  watch: {
    $route() {
      this.getEventDetails();
    },
  },
  created() {
    this.getEventDetails();
    this.getRelatedEvent();
  },
  methods: {
    getRelatedEvent() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}/similar/?order_by=-published_at`,
      ).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal event item getting');
        const {
          data: {data: data},
        } = response;
        this.relatedEvent = data;
      }).catch((err) => {
        console.log(err);
      });
    },
    getEventDetails() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
      ).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal event item getting');
        const {
          data: {data: data},
        } = response;
        this.event = {...data};
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
    margin-bottom: 40px;

    main {
      overflow: hidden;

      .title {
        margin-bottom: 40px;

        &_ribbon {
          font-family: Golos;
          font-size: 13px;
          font-style: normal;
          font-weight: 500;
          line-height: 16px;
          background: #8dc95e;
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
          margin-bottom: 16px;
        }

        &_type {
          //styleName: Text / 16|20 Regular • metioche;
          font-family: Golos;
          font-size: 16px;
          font-style: normal;
          font-weight: 400;
          line-height: 20px;
          margin-bottom: 32px;
        }

        &_date {
          color: var(--main-color-dark);
          //styleName: Headers / 24|28 Bold • crephusa;
          font-family: Golos;
          font-size: 24px;
          font-style: normal;
          font-weight: 800;
          line-height: 28px;
          margin-bottom: 16px;
        }

        &_contacts {
          display: flex;
          margin-bottom: 24px;

          svg {
            margin-right: 10px;
          }

          &-address {
            font-family: Golos;
            font-size: 16px;
            font-style: normal;
            font-weight: 500;
            line-height: 20px;
            display: flex;
            align-items: center;
            margin-right: 26px;
          }

          &-phone {
            font-family: Golos;
            font-size: 16px;
            font-style: normal;
            font-weight: 500;
            line-height: 20px;
            display: flex;
            align-items: center;
          }
        }

        &_univer-avatar {
          img {
            max-width: 170px;
            height: auto;
          }
        }
      }

      .news_item_content {
        margin-bottom: 32px;

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

      .news_item_viewers {
        display: flex;
        margin-bottom: 40px;

        .target_auditory {
          width: 50%;

          &_label {
            font-family: Golos;
            font-size: 13px;
            font-style: normal;
            font-weight: 400;
            line-height: 16px;
            margin-bottom: 4px;
            margin-right: 32px;
          }

          &_value {
            font-family: Golos;
            font-size: 16px;
            font-style: normal;
            font-weight: 800;
            line-height: 20px;
            color: var(--main-color);
          }
        }

        .taget_age {
          width: 50%;

          &_label {
            font-family: Golos;
            font-size: 13px;
            font-style: normal;
            font-weight: 400;
            line-height: 16px;
            margin-bottom: 4px;
          }

          &_value {
            font-family: Golos;
            font-size: 16px;
            font-style: normal;
            font-weight: 800;
            line-height: 20px;
            color: var(--main-color);
          }
        }
      }
        .news_item_content-text {
            margin-top: 40px;
         }

      .news_item_theme {
        .theme_label {
          font-family: Golos;
          font-size: 13px;
          font-style: normal;
          font-weight: 400;
          line-height: 16px;
          margin-bottom: 4px;
        }

        .theme_value {
          display: flex;

          &_item {
            font-family: Golos;
            font-size: 13px;
            font-style: normal;
            font-weight: 500;
            line-height: 16px;
            background: #81abee;
            color: var(--light-color);
            padding: 4px 12px;
            border-radius: 16px;
            margin-right: 8px;

            &:last-child {
              margin-right: 0;
            }
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
  }

  .news_item_speakers {
    margin-bottom: 54px;

    &-label {
      font-family: Golos;
      font-size: 24px;
      font-style: normal;
      font-weight: 800;
      line-height: 28px;
      margin-top: 40px;
      margin-bottom: 24px;
    }

    &-wrapper {
      display: grid;
      // grid-template-columns: repeat(4, 1fr);
    }

    .speaker {
      display: inline-block;
      width: min-content;

      &_avatar {
        width: 112px;
        height: 112px;
        background-size: cover;
        background-size: cover !important;
        background-repeat: no-repeat !important;
        border-radius: 50%;
        overflow: hidden;
        margin-bottom: 18px;
      }

      &_name {
        font-family: Golos;
        font-size: 20px;
        font-style: normal;
        font-weight: 800;
        line-height: 24px;
      }

      &_position {
        font-family: Golos;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 20px;
        white-space: nowrap;
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
