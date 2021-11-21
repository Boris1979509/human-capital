<template>
  <a
    class="rir-date-picker__column"
    :class="{
      active: isActive.isActive,
      start: isActive.isStart,
      finish: isActive.isFinish,
      period: $period(),
      block: isBlock,
      nowDate: nowDate,
      otherMonth: item.month !== month
    }"
    @click="$emit('selectDate', item)">
    {{ item.day }}
  </a>
</template>

<script>
export default {
  name: 'day-element',
  inject: ['$period', '$startDate'],
  props: {
    value: {
      required: true
    },
    item: {
      type: Object
    },
    year: {
      type: Number
    },
    month: {
      type: Number
    }
  },
  computed: {
    isActive () {
      const dateCalendar = new Date(this.year, this.item.month - 1, this.item.day, 0, 0, 0, 0).getTime()
      if (this.$period()) {
        const start = +new Date(this.value[0]).setHours(0, 0, 0, 0)
        const finish = +new Date(this.value[1]).setHours(0, 0, 0, 0)
        return {
          isActive: start <= dateCalendar &&
            finish >= dateCalendar,
          isStart: start === dateCalendar,
          isFinish: finish === dateCalendar
        }
      } else {
        const nowDay = new Date(this.value)
        return {
          isActive: nowDay && +nowDay.setHours(0, 0, 0, 0) === dateCalendar,
          isStart: false,
          isFinish: false
        }
      }
    },
    isBlock () {
      const dateCalendar = Date.UTC(this.item.year, this.item.month - 1, this.item.day, 0, 0, 0, 0)
      if (this.$startDate()) {
        return +new Date(this.$startDate()).setHours(0, 0, 0, 0) > dateCalendar
      }
      return false
    },
    nowDate () {
      const nowDate = new Date().toDateString()
      const dateCalendar = new Date(this.year, this.item.month - 1, this.item.day).toDateString()
      return nowDate === dateCalendar
    }
  }
}
</script>

<style scoped>

</style>
