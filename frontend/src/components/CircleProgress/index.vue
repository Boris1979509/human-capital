<template>
  <svg class="progress" width="84" height="84" viewBox="0 0 84 84">
    <circle class="progress__value" cx="42" cy="42" :r="radius" stroke-width="4"
            :style="`stroke: ${strokeColor}; stroke-dasharray: ${circumference}; stroke-dashoffset: ${currentProgress(progress)};`"/>
  </svg>
</template>

<script>
export default {
  name: "CircleProgress",

  props: {
    progress: {
      type: [Number, String],
      default: () => 0,
    }
  },

  computed: {
    circumference: function() {
      return 2 * Math.PI * this.radius
    },
    strokeColor: function() {
      if (this.progress < 30) {
        return '#E9A35D';
      }
      if (this.progress > 30 && this.progress < 65) {
        return '#FFF104';
      }
      if (this.progress > 65) {
        return '#3D75E4';
      }

      return '#3D75E4';
    }
  },

  data: function() {
    return {
      radius: 40,
    }
  },

  methods: {
    currentProgress: function(value) {
      const progress = value / 100;
      const dashoffset = this.circumference * (1 - progress);

      return dashoffset;
    }
  }
}
</script>
