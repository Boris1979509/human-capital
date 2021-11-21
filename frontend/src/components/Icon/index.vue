<template>
  <svg :style="style"
       :viewBox="viewport"
       class="icon">
    <use :xlink:href="`#${xlink}`"/>
  </svg>
</template>

<script>
export default {
  name: 'Icon',
  props: {
    xlink: {
      type: String,
      required: true,
    },
    viewport: {
      type: String,
      required: true,
    },
    width: {},
    height: {},
    sizeFree: {
      type: Boolean,
    },
    rotate: {
      type: Number,
    },
    fill: {},
  },
  computed: {
    style() {
      if (!this.sizeFree) {
        return {
          width: this.width ? this.width : `${this.viewport.split(' ')[2]}px`,
          height: this.height ? this.height : `${this.viewport.split(' ')[3]}px`,
          transform: this.rotate ? `rotate(${this.rotate}deg)` : '',
          fill: this.fill ? this.fill : '',
        };
      }
      return {
        width: this.width ? this.width : '',
        height: this.height ? this.height : '',
        transform: `rotate(${this.rotate})`,
      };
    },
  },
};
</script>
