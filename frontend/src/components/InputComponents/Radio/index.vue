<template>
  <div class="radio-container">
    <input
        :id="id"
        :name="name"
        :value="value"
        type="radio"
        @change="update"
    />

    <label :for="id">
      <div class="radio-container__text">
        {{ label }}
      </div>
    </label>
  </div>
</template>

<script>
export default {
  name: 'Radio',

  model: {
    prop: 'checked',
    event: 'change',
  },

  props: {
    id: {
      type: [String, Number],
    },
    name: {
      type: String,
    },
    label: {
      type: String,
    },
    value: {
      type: [String, Object],
    },
  },

  methods: {
    update() {
      this.$emit('change', this.value);
    },
  },
};
</script>
