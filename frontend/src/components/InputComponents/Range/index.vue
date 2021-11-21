<template>
  <div class="range-container" :style="`margin-bottom: ${margin}px`">
    <div class="range-container__label" v-if="label">
      <div class="range-container__text">
        {{ label }}
      </div>

      <div class="range-container__value">
        {{ currentValue }}
      </div>
    </div>

    <input type="range"
           :min="parseInt(min)"
           :max="parseInt(max)"
           :step="step"
           v-model="currentValue"
           :style="`background-image: ${backgroundBefore}`"
           @change="$emit('change', $event.target.value)"
           @mouseup="$emit('update', $event.target.value)">
  </div>
</template>

<script>
export default {
  name: 'Range',

  watch: {
    value(val) {
      this.currentValue = val;
    },
    currentValue(val) {
      this.fillBackgroundBefore(val);
    },
  },

  created() {
    this.currentValue = this.value;
    this.fillBackgroundBefore(this.currentValue);
  },

  props: {
    label: {
      type: String,
      default: () => '',
    },
    max: {
      type: [String, Number],
      default: () => 100,
    },
    min: {
      type: [String, Number],
      default: () => 0,
    },
    step: {
      type: [String, Number],
      default: () => 1,
    },
    margin: {
      type: Number,
      default: () => 0,
    },
    value: {
      type: [String, Number],
      default: () => 0,
    },
  },

  data: function() {
    return {
      currentValue: 0,
      backgroundBefore: '',
    }
  },

  methods: {
    fillBackgroundBefore: function(value) {
      const currentValue = (value - this.min) / (this.max - this.min);
      this.backgroundBefore = `-webkit-gradient(linear, 0% 0%, 100% 0%, color-stop(${currentValue}, #3D75E4), color-stop(${currentValue}, #C0D6F6));`;
    },
  },
}
</script>
