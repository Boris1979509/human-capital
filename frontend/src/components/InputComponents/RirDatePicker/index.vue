<template>
  <date-picker
      v-if="inline"
      :period="period"
      :start-date="startDate"
      :is-time="isTime"
      :is-time-zone="isTimeZone"
      :tz-local="tzLocal"
      v-model="model"
  />
  <rir-popover
      ref="popover"
      content-class="rir-date-picker__content"
      :disabled="readonly"
      v-model="isView"
      v-else>
    <template v-slot:activator="{ on }">
      <rir-input
          class="rir-date-picker__input"
          :class="{ period }"
          :label="label"
          @blur="checkFormat"
          :readonly="readonly || period"
          :value="dateFormat"
          @click="on.click">
        <a
            class="rir-select__icon"
            ref="icon"
            slot="after"
            tabindex="0"
        >
          <rir-icon
              :size="20"
              fill="rocky"
              icon="calendar"/>
        </a>
      </rir-input>
    </template>
    <date-picker
        @changeView="isView = false"
        :click-close="clickClose"
        :period="period"
        :start-date="startDate"
        :is-time="isTime"
        :is-time-zone="isTimeZone"
        :tz-local="tzLocal"
        v-model="model"/>
  </rir-popover>
</template>

<script>
import DatePicker from './components/DatePicker';
import RirPopover from '../RirPopover/index';
import RirInput from '../RirInput/index';
import RirIcon from '../RirIcon/index';

export default {
  name: 'rir-date-picker',
  components: {RirIcon, RirInput, RirPopover, DatePicker},
  mounted() {
    this.setTzLocal();
    if (this.value) {
      let val;
      const now = new Date();
      const start = new Date(now.getUTCFullYear(), now.getUTCMonth(), now.getUTCDate(),
          this.isTime ? now.getHours() : 0,
          this.isTime ? now.getMinutes() : 0,
          this.isTime ? now.getSeconds() : 0,
          this.isTime ? now.getMilliseconds() : 0);
      if (this.period) {
        val = this.value?.length ?
            this.value
            :
            [
              `${start.toLocaleDateString('en-CA')}${this.tzLocal}`,
              `${start.toLocaleDateString('en-CA')}${this.tzLocal}`,
            ];
      } else {
        val = this.value || start.getTime();
      }
      this.$emit('input', val);
    }
  },
  data: () => ({
    isView: false,
    tzLocal: '',
  }),
  props: {
    value: {
      type: [Number, Array, Object, String],
    },
    label: {
      type: String,
    },
    inline: {
      type: Boolean,
    },
    readonly: {
      type: Boolean,
    },
    clickClose: {
      type: Boolean,
    },
    period: {
      type: Boolean,
    },
    startDate: {
      type: String,
    },
    isTime: {
      type: Boolean,
    },
    isTimeZone: {
      type: Boolean,
    },
  },
  computed: {
    dateFormat() {
      if (this.value) {
        if (this.period) {
          const start = this.toLocaleUTCDateString(this.model[0], 'ru-Ru');
          const finish = this.toLocaleUTCDateString(this.model[1], 'ru-Ru');
          return `${start || ''} - ${finish || ''}`;
        } else {
          return this.toLocaleUTCDateString(this.model, 'ru-Ru');
        }
      }
      return null;
    },
    model: {
      get() {
        if (this.value) {
          return this.value;
        }
        return null;
      },
      set(val) {
        this.$emit('input', val);
      },
    },
  },
  methods: {
    openPopover() {
      this.$refs.popover.on.click();
    },
    async checkFormat(val) {
      if (this.period) return;
      const timeOld = this.value;
      await this.$emit('input', 0);
      let re;
      if (this.isTime) {
        re = /^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2}), (\d{2}):(\d{2})\s*$/;
      } else {
        re = /^\s*(3[01]|[12][0-9]|0?[1-9])\.(1[012]|0?[1-9])\.((?:19|20)\d{2})\s*$/;
      }
      if (re.test(val)) {
        const matchDate = val.match(re);
        const utc = new Date(
            matchDate[3],
            matchDate[2] - 1,
            matchDate[1],
            this.isTime ? matchDate[4] : 0,
            this.isTime ? matchDate[5] : 0,
            0,
            0);
        if (this.startDate && +new Date(this.startDate).setHours(0, 0, 0, 0) > utc) {
          this.$emit('input', val ? timeOld : null);
          return;
        }
        this.$emit('input', this.isTime ? utc.getTime() : utc.toLocaleDateString('en-CA'));
      } else {
        await this.$emit('input', val ? timeOld : null);
      }
    },
    toLocaleUTCDateString(date, locales, options) {
      let d = date;
      if (date && this.isTimeZone) {
        const regexp = new RegExp('(\\d{4}-\\d{2}-\\d{2})', 'gm');
        d = date.match(regexp);
      }
      const adjustedDate = new Date((this.isTime)
          ? +d
          : d);
      if (adjustedDate instanceof Date && !isNaN(adjustedDate)) {
        if (this.isTime) {
          options = {year: 'numeric', month: 'numeric', day: 'numeric', hour: 'numeric', minute: 'numeric'};
        }
        return adjustedDate.toLocaleDateString(locales, options);
      }
      return null;
    },
    setTzLocal() {
      if (this.isTimeZone) {
        const tz = new Date().getTimezoneOffset();
        const timeZone = Math.abs(tz);
        const hours = Math.floor(timeZone / 60);
        const minutes = timeZone - hours * 60;
        this.tzLocal = `Z${tz <= 0 ? '+' : '-'}${('0' + hours).slice(-2)}:${('0' + minutes).slice(-2)}`;
      }
    },
  },
};
</script>
<style>
.error .rir-input {
  background-color: var(--error-bg);
  color: rgba(52, 4, 15, .72);
}
</style>