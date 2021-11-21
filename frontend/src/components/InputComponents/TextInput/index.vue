<template>
  <div class="input-group" :class="classSpecial" :style="`margin-bottom: ${margin}px`" @keyup.enter="$emit('enter')">
    <label :for="id" v-if="isLabel">
      {{ label }}
    </label>

    <input v-if="!textarea && inputMask"
           :type="type"
           :id="id"
           v-model="inputValue"
           :disabled="disabled"
           autocomplete="new-password"
           :required="required"
           v-mask="inputMask"
    />

    <input v-else-if="!textarea && !inputMask"
           :type="type"
           :id="id"
           v-model="inputValue"
           :disabled="disabled"
           autocomplete="new-password"
           :required="required"
    />

    <textarea v-else
              v-model="inputValue"
              :id="id"
              :required="required"
              :disabled="disabled">
            </textarea>

    <span class="input-group__placeholder" :class="disabled && inputValue ? 'hasValue' : ''">
      {{ placeholder }}
    </span>
  </div>
</template>

<script>
import { mask } from 'vue-the-mask';

export default {
  name: 'Input',

  props: {
    id: {
      type: String
    },
    classSpecial: {
      type: String
    },
    isLabel: {
      type: Boolean,
      default: () => true,
    },
    label: {
      type: String
    },
    placeholder: {
      type: String
    },
    type: {
      type: String,
      default: () => 'text',
    },
    textarea: {
      type: Boolean,
    },
    margin: {
      type: Number,
      default: () => 20,
    },
    inputMask: {
      type: [Number, String],
    },
    required: {
      type: Boolean,
      default: () => true
    },
    disabled: {
      type: Boolean,
    },
    value: {}
  },

  directives: {
    mask,
  },

  computed: {
    inputValue: {
      get() {
        return this.value;
      },
      set(string) {
        this.$emit('input', string);
      },
    },
  },
}
</script>
