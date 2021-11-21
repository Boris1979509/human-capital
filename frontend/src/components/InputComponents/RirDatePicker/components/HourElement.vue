<template>
    <span
      :class="{active: isActiveHour}"
      @click="$emit('setHour', numberHour - 1)"
    >
      {{ numberHour - 1 }}
    </span>
</template>

<script>
export default {
  name: 'hour-element',
  inject: ['$value'],
  props: {
    numberHour: {
      type: Number
    }
  },
  computed: {
    isActiveHour () {
      return this.numberHour - 1 === new Date(+this.$value()).getHours()
    }
  }
}
</script>

<style scoped>

</style>
