<template>
  <div v-if="selected">
    <div class="login-app__container">
      <div class="login-app__back" v-if="isBack">
        <Button @click.native="$emit('onTurnBack')" class="link-svg">
          <Icon xlink="back"
                viewport="0 0 6 16" />
          Назад
        </Button>
      </div>

      <div class="login-app__header">
        {{ title }}
      </div>

      <div class="login-app__sub-title" v-if="subTitle">
        {{ subTitle }}
      </div>

      <div class="login-app__desc" v-if="description">
        {{ description }}
      </div>

      <div class="login-app__inputs">
        <slot name="inputs" />
      </div>

      <slot name="submit" />
    </div>
  </div>
</template>

<script>
export default {
  name: "LoginStep",

  props: {
    selected: {
      type: Boolean,
      default: false,
    },
    title: {
      type: String,
    },
    subTitle: {
      type: String,
    },
    description: {
      type: String,
    },
    isBack: {
      type: Boolean,
      default: false,
    },
  },
}
</script>
