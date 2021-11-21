<template>
  <router-link :to="`/opinions/${id}`" class="person-card person-card--small">
    <div class="person-card__main">
      <div class="person-card__image">
        <img :src="image" alt="" v-if="image">
      </div>

      <div class="person-card__info">
        <h5 class="person-card__title title" v-line-clamp="2">
          {{ title }}
        </h5>

        <div class="person-card__name">
          {{ name }}
        </div>

        <div class="person-card__position">
          {{ position }}
        </div>
      </div>
    </div>
  </router-link>
</template>

<script>
export default {
  name: 'PersonCardSmall',

  props: {
    id: {
      type: [Number, String],
    },
    image: {
      type: String,
      default: () => '',
    },
    name: {
      type: String,
    },
    position: {
      type: String,
    },
    title: {
      type: String,
    },
  },
}
</script>
