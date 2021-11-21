<template>
    <span
      :class="{active: isActiveMinutes}"
      @click="$emit('setMinute', numberMinutes - 1)"
    >
      {{ numberMinutes - 1 }}
    </span>
</template>

<script>
export default {
  name: 'minutes-element',
  inject: ['$value'],
  props: {
    numberMinutes: {
      type: Number
    }
  },
  computed: {
    isActiveMinutes () {
      return this.numberMinutes - 1 === new Date(+this.$value()).getMinutes()
    }
  }
}
</script>

<style scoped>

</style>
