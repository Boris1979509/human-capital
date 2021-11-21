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
            <EmployeeCard :key="item.id"
                          :avatar="item.avatar"
                          :first-name="item.first_name"
                          :last-name="item.last_name"
                          :position="item.position"
            />
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmployeesSlider',

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

      if (tmpArray.length > 4) {
        const splittedList = [];
        while (tmpArray.length) {
          let pack = tmpArray.splice(0, 4);
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
$slider-height: 212px;
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
      grid-template-columns: repeat(4, 1fr);
      grid-gap: 32px;
      justify-items: center;
    }
  }
}
</style>
