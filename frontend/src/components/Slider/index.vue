<template>
  <div class="slider_wrapper">
    <a v-on:click.prevent="prevSlide" class="slider_arr_left">
      <Icon xlink="sliderArrLeft" viewport="0 0 12 32" />
    </a>
    <a v-on:click.prevent="nextSlide" class="slider_arr_right">
      <Icon xlink="sliderArrRight" viewport="0 0 12 32" />
    </a>

    <div ref="slider" class="slider js-slider">
      <div
        class="slider__body"
        v-bind:style="{ left: sliderOffsetLeft + 'px' }"
      >
        <div
          ref="slide"
          class="slider__slide js-slide"
          v-for="(block, idx) in splittedArray"
          :key="idx"
        >
          <div class="item" v-for="(item, idx) in block" :key="idx">
            <template v-if="!item.isAddBtn">
              <div
                class="item_img"
                :style="
                  'background-image: url(' +
                    ((item.cover && item.cover.url) || testImg) +
                    ')'
                "
              >
                <div
                  class="item_edit"
                  @click="
                    $router.push({
                      name: editRouteName,
                      params: {
                        target: 'edit',
                        id: item.id,
                        type: item.type.id,
                      },
                    })
                  "
                >
                  <Icon xlink="edit" viewport="0 0 16 16" />
                </div>
                <div class="item_ribbon" :style="`background: ${ribbonColor}`">
                  {{ ribbonLabel }}
                </div>
              </div>
              <div class="item_label">
                {{ item.title }}
              </div>
              <div class="item_date">
                {{ item.published_at | date }}
              </div>
            </template>
            <template v-else>
              <div
                class="add_item_icon"
                @click="$router.push({ name: routeName })"
              >
                <Icon xlink="add" viewport="0 0 48 48" />
              </div>
              <div
                class="add_item_text"
                @click="$router.push({ name: routeName })"
              >
                {{ addBtnLabel }}
              </div>
            </template>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const testImg = require('@/assets/svg/no_foto.svg');
export default {
  name: 'Slider',
  props: {
    array: {
      type: Array,
      default: () => {
        [];
      },
    },
    ribbonLabel: String,
    ribbonColor: String,
    addBtnLabel: String,
    routeName: String,
    editRouteName: String,
  },
  computed: {
    splittedArray() {
      let tmpArray = JSON.parse(JSON.stringify(this.array));
      tmpArray.unshift({
        isAddBtn: true,
        label: 'Добавить',
      });
      if (tmpArray.length > 3) {
        const splittedList = [];
        while (tmpArray.length) {
          let pack = tmpArray.splice(0, 3);
          splittedList.push(pack);
        }
        this.initSlider();
        return splittedList;
      } else {
        return [[...tmpArray]];
      }
    },
  },
  data() {
    return {
      testImg,
      // Всего слайдов
      sliderAllCount: 0,
      // Номер активного слайда
      sliderActive: 1,
      // Отступ тела со слайдами в контейнере
      sliderOffsetLeft: 0,
      // Шаг одного слайда = его длина
      sliderOffsetStep: 0,
      // Список изображений
    };
  },
  methods: {
    // Иницилизация слайдера
    initSlider: function() {
      // Получаем элементы сладера и его слайдов
      let sliderBody = this.$refs.slider;
      // Записываем длину одного слайда для перелистывания
      this.sliderOffsetStep = sliderBody && sliderBody.clientWidth;
      // Общее количество слайдов для стопов
      // this.sliderAllCount = sliderSlidies && sliderSlidies.length;
      if (this.array.length > 2) {
        this.sliderAllCount =
          (this.array.length + 1) % 3
            ? Math.floor((this.array.length + 1) / 3) + 1
            : (this.array.length + 1) / 3;
      } else this.sliderAllCount = 1;
    },

    // Открыть слайд по номеру
    openSlide: function(id) {
      if (id > 0 && id <= this.sliderAllCount) {
        this.sliderActive = id;
        // Сдвигаем элемент со слайдами
        this.sliderOffsetLeft = -(
          this.sliderActive * this.sliderOffsetStep -
          this.sliderOffsetStep
        );
      }
    },

    // Следующий слайд
    nextSlide: function() {
      if (this.sliderActive < this.sliderAllCount) {
        this.sliderActive += 1;
        this.openSlide(this.sliderActive);
      }
    },

    // Предыдущий слайд
    prevSlide: function() {
      if (this.sliderActive > 1) {
        this.sliderActive -= 1;
        this.openSlide(this.sliderActive);
      }
    },
  },

  mounted() {
    this.initSlider();
    // Перенастройка слайдера при ресайзе окна
    window.addEventListener('resize', () => {
      this.initSlider();
      this.openSlide(this.sliderActive);
    });
  },
};
</script>

<style lang="scss" scoped>
@import '@/assets/scss/utilities/_mixins.scss';
$slider-height: 290px;
$slide-width: 100%;
.slider_wrapper {
  position: relative;
  .slider_arr_left {
    position: absolute;
    left: -28px;
    top: 26%;
    cursor: pointer;
  }
  .slider_arr_right {
    position: absolute;
    right: -28px;
    top: 26%;
    cursor: pointer;
  }
  .slider {
    width: 100%;
    height: $slider-height;
    position: relative;
    overflow: hidden;

    &__body {
      min-width: auto;
      height: $slider-height;
      display: flex;
      position: relative;
      align-items: stretch;
      transition: all 0.5s ease;
    }

    &__slide {
      min-width: $slide-width;
      height: $slider-height;
      background-size: cover;
      background-position: center;
      // flex: 1 100%;
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-gap: 32px;
      justify-items: center;
    }

    .item {
      width: 293px;
      display: inline-block;
      cursor: pointer;
      &_img {
        width: 100%;
        height: 183px;
        // background: url("../../../assets/img/news_example.png") no-repeat;
        background-size: cover;
        background-repeat: no-repeat;
        margin-bottom: 16px;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
        .item_edit {
          width: 80px;
          height: 80px;
          position: absolute;
          background-color: var(--main-color-dark-trans-light);
          border-radius: 16px;
          top: -40px;
          right: -40px;
          &:hover {
            background-color: var(--main-color-dark);
          }
          .icon {
            position: absolute;
            left: 12px;
            bottom: 12px;
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

      .add_item_icon {
        display: flex;
        background: var(--color-rush);
        width: 293px;
        height: 183px;
        align-items: center;
        justify-content: center;
        border-radius: 16px;
        margin-bottom: 16px;
      }
      .add_item_text {
        display: inline-block;
        font-family: Golos;
        font-style: normal;
        font-weight: 800;
        font-size: 20px;
        line-height: 28px;
        text-align: center;
        color: #214eb0;
      }
    }
  }
}
</style>
