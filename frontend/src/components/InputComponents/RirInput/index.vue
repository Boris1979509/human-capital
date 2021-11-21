<template>
  <section
      class="rir-input"
      :class="{
      readonly
    }"
      :style="{
      maxWidth
    }"
      @click.stop="focusInput">
    <div
        :class="{
        'not-label': isLabel
      }"
        class="rir-input__body">
      <div class="rir-input__input">
        <input
            v-bind="paramsInput"
            :disabled="readonly"
            :placeholder="placeholder"
            :type="type"
            :min="minNumber"
            :max="maxNumber"
            v-model="model"
            @blur="$emit('blur', $event.target.value)"
            @keyup="$emit('keyup', $event.target.value)"
            ref="input"/>
        <label
            class="rir-input__label"
            :class="{
            active: isModel
          }"
            v-if="isLabel">
          {{ label }}
        </label>
      </div>
    </div>
    <div class="rir-input__before" v-if="$slots.before">
      <slot name="before"></slot>
    </div>
    <div class="rir-input__after" v-if="$slots.after">
      <slot name="after"></slot>
    </div>
  </section>
</template>

<script>
export default {
  name: 'rir-input',
  props: {
    value: {
      validator: prop => ['string', 'number'].includes(typeof prop) || prop === null,
      default: '',
    },
    label: {
      type: String,
      default: '',
    },
    placeholder: {
      type: String,
      default: '',
    },
    maxWidth: {
      type: String,
      default: '100%',
    },
    paramsInput: {
      type: Object,
      default: () => ({
        type: 'input',
      }),
    },
    readonly: {
      type: Boolean,
    },
    checkValidInput: {
      type: Function,
      default: () => null,
    },
    type: {
      type: String,
      default: 'text',
    },
    minNumber: {
      type: [String, Number],
      default: '0',
    },
    maxNumber: {
      type: [String, Number],
      default: '1000',
    },
  },
  computed: {
    model: {
      get() {
        return this.value;
      },
      set(val) {
        const newVal = this.checkValidInput(val.toString()) || val.toString();
        this.$refs.input.value = newVal;
        this.$emit('input', newVal || null);
      },
    },
    isModel() {
      return this.value !== null;
    },
    isLabel() {
      return !this.placeholder && !!this.label;
    },
  },
  methods: {
    focusInput(e) {
      this.$emit('click', e);
      if (this.readonly) return;
      this.$refs.input.focus();
    },
  },
};
</script>
