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
          :style="'grid-template-columns: repeat(' + rowItemsCount + ', 1fr);'"
          v-for="(block, idx) in splittedArray"
          :key="idx"
        >
          <div class="item" v-for="(item, idx) in block" :key="idx">
            <div
              class="item_img"
              @click="test(item.id)"
              :style="
                'background-image: url(' +
                  ((item.cover && item.cover.url) || testImg) +
                  ')'
              "
            ></div>
            <div class="item_label">
              {{ item.title }}
            </div>
            <div class="item_date">
              {{ item.published_at | date }}
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const testImg = require('@/assets/svg/no_foto.svg');
export default {
  name: 'RelatedSlider',
  props: {
    array: {
      type: Array,
      default: () => {
        [];
      },
    },
    // ribbonLabel: String,
    // ribbonColor: String,
    // addBtnLabel: String,
    rowItemsCount: {
      type: Number,
      default: () => 4,
    },
    routeName: String,
    // editRouteName: String,
  },
  computed: {
    splittedArray() {
      let tmpArray = JSON.parse(JSON.stringify(this.array));
      // tmpArray.unshift({
      //   isAddBtn: true,
      //   label: 'Добавить',
      // });
      if (tmpArray.length > this.rowItemsCount) {
        const splittedList = [];
        while (tmpArray.length) {
          let pack = tmpArray.splice(0, this.rowItemsCount);
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
    test: function(id) {
      this.$router.push({ name: this.routeName, params: { id } });
    },
    // Иницилизация слайдера
    initSlider: function() {
      // Получаем элементы сладера и его слайдов
      let sliderBody = this.$refs.slider;
      // Записываем длину одного слайда для перелистывания
      this.sliderOffsetStep = sliderBody && sliderBody.clientWidth;
      // Общее количество слайдов для стопов
      // this.sliderAllCount = sliderSlidies && sliderSlidies.length;
      if (this.array.length > this.rowItemsCount) {
        this.array.length % this.rowItemsCount
          ? (this.sliderAllCount = Math.floor(this.array.length / this.rowItemsCount) + 1)
          : this.array.length / this.rowItemsCount;
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
      width: 212px;
      display: inline-block;
      cursor: pointer;
      &_img {
        width: 100%;
        height: 132px;
        // background: url("../../../assets/img/news_example.png") no-repeat;
        background-size: cover;
        background-repeat: no-repeat;
        margin-bottom: 16px;
        border-radius: 16px;
        position: relative;
        overflow: hidden;
      }
      &_label {
        font-family: Golos;
        font-size: 16px;
        font-style: normal;
        font-weight: 800;
        line-height: 20px;
        letter-spacing: 0em;
        text-align: left;
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
