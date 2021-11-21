<template>
  <section class="rir-date-picker">
    <div class="rir-date-picker__navigation">
      <rir-icon icon="forward" @click.native="changeYear('DOWN')"/>
      <rir-icon icon="arrow-left" @click.native="changeMonth('DOWN')"/>
      <span
        class="rir-date-picker__title">
        {{listMonth[month - 1]}}
      </span>
      <span
        class="rir-date-picker__title">
        {{ year }}
      </span>
      <rir-icon
        v-if="isTime"
        class="rir-date-picker__clock"
        icon="clock"
        :fill="timePicker ? 'matrix' : 'rocky'"
        @click.native="openTime"
      />
      <rir-icon icon="arrow-right" @click.native="changeMonth('UP')"/>
      <rir-icon icon="forward" @click.native="changeYear('UP')"/>
    </div>
    <template  v-if="!timePicker">
      <div class="rir-date-picker__week">
      <span
        class="rir-date-picker__day"
        v-for="name in listWeek"
        :key="name"
      >{{ name }}</span>
      </div>
      <div class="rir-date-picker__days">
        <div
          class="rir-date-picker__row"
          v-for="(daysInWeek, index) in arrayDayInMonth"
          :key="index">
          <day-element
            v-for="(d, index) in daysInWeek"
            :item="d"
            :year="year"
            :month="month"
            :value="model"
            :period="period"
            :key="index"
            @selectDate="selectDate"
          />
        </div>
      </div>
    </template>
    <div class="rir-date-picker__time" v-if="isTime && timePicker">
      <div class="hour">
        <hour-element
          v-for="n in 24"
          :number-hour="n"
          :key="n"
          @setHour="setHour"
        />
      </div>
      <div class="minutes">
        <minutes-element
          v-for="n in 60"
          :key="n"
          :number-minutes="n"
          @setMinute="setMinute"
        />
      </div>
    </div>
  </section>
</template>

<script>
import RirIcon from '../../RirIcon/index'
import DayElement from './DayElement'
import HourElement from './HourElement'
import MinutesElement from './MinutesElement'
export default {
  name: 'date-picker',
  components: { MinutesElement, HourElement, DayElement, RirIcon },
  mounted () {
    if (this.model) {
      let date = null
      if (this.period) {
        date = this.model[0]
      } else {
        date = this.model
      }
      this.year = new Date(date).getFullYear()
      this.month = new Date(date).getMonth() + 1
    }
  },
  data: () => ({
    timePicker: false,
    year: new Date().getFullYear(),
    month: new Date().getMonth() + 1,
    listWeek: ['Пн', 'Вт', 'Ср', 'Чт', 'Пт', 'Сб', 'Вс'],
    listMonth: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь']
  }),
  provide () {
    return {
      $value: () => this.value,
      $period: () => this.period,
      $startDate: () => this.startDate
    }
  },
  props: {
    value: {
      required: true
    },
    clickClose: {
      type: Boolean
    },
    period: {
      type: Boolean
    },
    startDate: {
      type: String
    },
    isTime: {
      type: Boolean
    },
    isTimeZone: {
      type: Boolean
    },
    tzLocal: {
      type: String
    }
  },
  computed: {
    // model внутри календаря
    model: {
      get () {
        let val = null
        const regexp = new RegExp('(\\d{4}-\\d{2}-\\d{2})', 'gm')
        if (Array.isArray(this.value)) {
          val = this.value.map(el => el.match(regexp)[0])
        } else {
          if (this.isTime) {
            val = +this.value
          } else {
            val = (!this.value) ? this.value : this.value.match(regexp)[0]
          }
        }
        return val
      },
      set (val) {
        let emit = null
        if (Array.isArray(val)) {
          emit = val.map(el => `${el}${this.tzLocal}`)
        } else {
          emit = `${val}${this.tzLocal}`
        }
        this.$emit('input', emit)
      }
    },
    // количество дней в предыдущем месяце
    daysCountInPrevMonth () {
      return new Date(this.year, this.month - 1, 0).getDate()
    },
    // количество дней в месяце
    daysCountInMonth () {
      return new Date(this.year, this.month, 0).getDate()
    },
    // первый день недели в мясеце
    startDayInMonth () {
      let day = new Date(this.year, this.month - 1, 1).getDay()
      day = [0, 1].includes(day)
        ? day === 0 ? 6 : 0
        : day - 1
      return day
    },
    // Количество недель в месяце
    weekCount () {
      const firstOfMonth = new Date(this.year, this.month - 1, 1)
      const lastOfMonth = new Date(this.year, this.month, 0)
      const used = firstOfMonth.getDay() + lastOfMonth.getDate()
      return Math.ceil(used / 7)
    },
    // Структура для вывода календаря
    arrayDayInMonth () {
      const arrData = []
      let lastDays = 1
      let month = this.month
      let year = this.year
      let type = null
      for (let w = 0; w < 6; w++) {
        arrData.push([])
        let daysWeek = 0
        if (w === 0) {
          for (let d = 1; d <= this.startDayInMonth; d++) {
            arrData[w].push({
              month: month - 1 > 0 ? month - 1 : 12,
              year: month - 1 > 0 ? year : year - 1,
              day: this.daysCountInPrevMonth - this.startDayInMonth + d,
              type: 'DOWN'
            })
          }
          daysWeek = this.startDayInMonth
        }
        for (let d = lastDays; d <= this.daysCountInMonth; d++) {
          daysWeek++
          arrData[w].push({
            month,
            day: d,
            year,
            type
          })
          if (daysWeek === 7) {
            if (this.daysCountInMonth === d) {
              lastDays = 1
              d = 0
              type = 'UP'
              year = month + 1 <= 12 ? year : year + 1
              month = month + 1 <= 12 ? month + 1 : 1
              break
            } else {
              lastDays = ++d
              break
            }
          } else if (this.daysCountInMonth === d && daysWeek < 7) {
            d = 0
            type = 'UP'
            year = month + 1 <= 12 ? year : year + 1
            month = month + 1 <= 12 ? month + 1 : 1
          }
        }
      }
      return arrData
    }
  },
  methods: {
    selectDate (d) {
      const now = new Date()
      const selectDay = new Date(
        d.year,
        d.month - 1,
        d.day,
        this.isTime ? new Date(this.model || now).getHours() : 0,
        this.isTime ? new Date(this.model || now).getMinutes() : 0,
        0,
        0)
      let setDate
      if (this.isTime) {
        setDate = selectDay.getTime()
      } else {
        setDate = `${selectDay.toLocaleDateString('en-CA')}`
      }
      if (this.month !== d.month) this.month = d.month
      if (this.year !== d.year) this.year = d.year
      if (this.period) {
        let val = [...this.model]
        if (this.model.length > 1 && val[0] !== val[1]) {
          val = []
          val[0] = setDate
          val[1] = setDate
        } else {
          if (+new Date(val[0]) < +selectDay) {
            val[1] = setDate
          } else {
            val[1] = val[0]
            val[0] = setDate
          }
        }
        this.model = val
        // this.$emit('input', val)
      } else {
        this.model = setDate
        // this.$emit('input', setDate)
      }
      this.clickClose && this.$emit('changeView')
    },
    setHour (n) {
      const date = new Date(+this.model)
      this.model = date.setHours(n)
      // this.$emit('input', date.setHours(n))
    },
    setMinute (n) {
      const date = new Date(+this.model)
      this.model = date.setMinutes(n)
      // this.$emit('input', date.setMinutes(n))
    },
    changeMonth (type) {
      switch (type) {
        case 'UP':
          if (this.month + 1 <= 12) {
            this.month++
          } else {
            this.month = 1
            this.year++
          }
          break
        case 'DOWN':
          if (this.month - 1 >= 1) {
            this.month--
          } else {
            this.month = 12
            this.year--
          }
          break
      }
    },
    changeYear (type) {
      switch (type) {
        case 'UP':
          this.year++
          break
        case 'DOWN':
          this.year--
          break
      }
    },
    openTime () {
      // !this.value && this.$emit('input', new Date().getTime())
      !this.model && (this.model = new Date().getTime())
      this.timePicker = !this.timePicker
    }
  }
}
</script>
