<template>
  <button :disabled="disabled"
          :style="styleComponent"
          :type="buttonType"
          class="btn"
          :class="isSuccess ? 'btn--success' : ''">
    <slot/>

    <div v-if="isSpinner" class="spinner"></div>

    <div class="success" v-if="isSuccess">
      <Icon xlink="success"
            :sizeFree="true"
            width="100%"
            height="100%"
            viewport="0 0 16 16"
      />
    </div>
  </button>
</template>

<script>
export default {
  name: 'Button',

  props: {
    full: {
      type: Boolean,
    },
    disabled: {
      type: Boolean,
    },
    buttonType: {
      type: String,
      default: () => 'button'
    },
    color: {
      type: String,
    },
    isSpinner: {
      type: Boolean,
    },
    isSuccess: {
      type: Boolean,
    },
    styleComponent: {},
  },
};
</script>
