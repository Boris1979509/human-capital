<template>
  <div class="compilation__item event">
    <div class="item__body">
      <div
          class="item__title"
          @click="
          $router.push({
            name: 'MainViewJournalEvent',
            params: {
              id: info.selectionable_id,
            },
          })
        "
      >
        <div class="item__title__text">
          {{ info.selectionable_data && info.selectionable_data.title }}
          <IconCustom :filename="'selections/arrow'"/>
        </div>
      </div>
      <div class="item__date event">
        {{
          info.selectionable_data &&
          info.selectionable_data.date_start | dateTime
        }}
      </div>
      <div class="item__contacts">
        <div
            class="item__contacts__address"
            v-if="info.selectionable_data && info.selectionable_data.address"
        >
          <Icon xlink="map_marker" viewport="0 0 12 16"/>
          {{ info.selectionable_data.address }}
        </div>
        <div
            class="item__contacts__phone"
            v-if="info.selectionable_data && info.selectionable_data.phone"
        >
          <Icon xlink="phone" viewport="0 0 16 16"/>
          {{ info.selectionable_data.phone }}
        </div>
      </div>
      <div class="item__viewers">
        <div class="item__target__auditory">
          <div class="item__target__auditory_label">Целевая аудитория</div>
          <div class="item__target__auditory_value">
            {{
              getEventTargetAudience
            }}
          </div>
        </div>
        <div class="item__target__age">
          <div class="item__target__age__label">Возраст участников</div>
          <div class="item__target__age__value">
            {{
              getEventParticipantsAge
            }}
          </div>
        </div>
      </div>
      <div class="item__theme">
        <div class="item__theme__label">Тематика</div>
        <div
            class="item__theme__value"
            v-if="info.selectionable_data && info.selectionable_data.tags"
        >
          <div
              v-for="(item, idx) of info.selectionable_data.tags"
              :key="idx"
              class="item__theme__value__item"
          >
            {{ item }}
          </div>
        </div>
      </div>
    </div>
    <!-- <div class="item__label">Мероприятие</div> -->
  </div>
</template>

<script>
export default {
  name: 'eventComponent',
  props: {
    info: Object,
  },
  computed: {
    getEventTargetAudience() {
      let audienceList = [];
      if (this.info.selectionable_data?.target_audience?.length) {
        audienceList = this.info.selectionable_data.target_audience.reduce((acc, item) => {
          const tmpItem = `${item.name}`;
          acc.push(tmpItem);
          return acc;
        }, []);
      }
      if (!audienceList.length) return '';
      else if (audienceList.length === this.$dictionaries.inst_auditory_journal.length)
        return 'Все';
      else return audienceList.join(', ');
    },
    getEventParticipantsAge() {
      let ageList = [];
      if (this.info.selectionable_data?.participants_age?.length) {
        ageList = this.info.selectionable_data.participants_age.reduce((acc, item) => {
          const tmpItem =
              item.name.indexOf('+') !== -1 ? `${item.name}` : `${item.name} лет`;
          acc.push(tmpItem);
          return acc;
        }, []);
      }
      if (!ageList.length) return '';
      else if (ageList.length === this.$dictionaries.inst_age.length)
        return 'Все';
      else return ageList.join(', ');
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

    //end util styles
    main {
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
      }

      .description {
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
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
    // display: grid;
    // grid-template-columns: 8fr 4fr;
    // grid-gap: 32px;
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
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            grid-gap: 32px;

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

          &__text {
            margin-top: 32px;
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
