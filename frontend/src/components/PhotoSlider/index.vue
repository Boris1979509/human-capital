<template>
  <div class="slider_wrapper">
    <a
      v-on:click.prevent="prevSlide"
      v-if="array.length > 1"
      class="slider_arr_left"
    >
      <Icon xlink="sliderArrLeftWhite" viewport="0 0 12 32" />
    </a>
    <a
      v-on:click.prevent="nextSlide"
      v-if="array.length > 1"
      class="slider_arr_right"
    >
      <Icon xlink="sliderArrRightWhite" viewport="0 0 12 32" />
    </a>
    <div class="slider_counter" v-if="array.length > 1">
      {{ sliderActive }} из {{ sliderAllCount }}
    </div>
    <div ref="slider" class="slider js-slider">
      <div
        class="slider__body"
        v-bind:style="{ left: sliderOffsetLeft + 'px' }"
      >
        <div
          ref="slide"
          class="slider__slide js-slide"
          v-for="(item, idx) in array"
          :style="'background-image: url(' + (item && item.url) + ')'"
          :key="idx"
        ></div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PhotoSlider',
  props: {
    array: {
      type: Array,
      default: () => {
        [];
      },
    },
    currentSlide: {
      type: Number,
      default: 1,
    },
    // ribbonLabel: String,
    // ribbonColor: String,
    // addBtnLabel: String,
    // routeName: String,
    // editRouteName: String,
  },
  data() {
    return {
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
      // let sliderSlidies = this.$refs.slide;
      // Записываем длину одного слайда для перелистывания
      this.sliderOffsetStep = sliderBody && sliderBody.clientWidth;
      // Общее количество слайдов для стопов
      this.sliderAllCount = this.array.length;
      this.$emit('update:currentSlide', this.currentSlide);
      this.openSlide(this.currentSlide);
      // if (this.array && this.array.length > 2) {
      //   (this.array.length + 1) % 3
      //     ? (this.sliderAllCount = (this.array.length + 1) / 3 + 1)
      //     : (this.array.length + 1) / 3;
      // } else this.sliderAllCount = 1;
    },

    // Открыть слайд по номеру
    openSlide: function(id) {
      if (id > 0 && id <= this.sliderAllCount) {
        this.sliderActive = id;
        this.$emit('update:currentSlide', id);
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
  watch: {
    array: {
      handler: function() {
        this.initSlider();
      },
      immediate: true,
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
$slider-height: 390px;
$slide-width: 100%;
.slider_wrapper {
  position: relative;
  border-radius: 16px;
  overflow: hidden;
  .slider_arr_left {
    position: absolute;
    left: 20px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    z-index: 1;
  }
  .slider_arr_right {
    position: absolute;
    right: 20px;
    top: 50%;
    transform: translateY(-50%);
    cursor: pointer;
    z-index: 1;
  }
  .slider_counter {
    background: rgba(#fff, 0.48);
    position: absolute;
    bottom: 12px;
    z-index: 1;
    line-height: 1;
    padding: 8px;
    border-radius: 10px;
    left: 50%;
    transform: translateX(-50%);
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
