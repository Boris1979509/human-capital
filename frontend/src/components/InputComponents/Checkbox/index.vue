<template>
  <div class="checkbox-container"
       :style="`margin-bottom: ${margin}px`"
       :class="indeterminate ? 'checkbox-container--partial' : ''">
    <input
        :id="id"
        type="checkbox"
        :checked="checked"
        @change="$emit('change', $event.target.checked)"
    />

    <label :for="id">
      <div class="checkbox-container__text">
        {{ label }}
      </div>
    </label>

    <div class="checkbox-container__desc">
      {{ description }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'Checkbox',

  model: {
    prop: 'checked',
    event: 'change',
  },

  props: {
    id: {
      type: [String, Number],
    },
    checked: {
      type: Boolean,
    },
    indeterminate: {
      type: Boolean,
      default: () => false,
    },
    label: {
      type: String,
    },
    description: {
      type: String,
    },
    margin: {
      type: Number,
      default: () => 0,
    },
  }
}
</script>
