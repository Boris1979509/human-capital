<template>
  <router-link :to="`/opinions/${id}`" class="person-card">
    <div class="person-card__main">
      <div class="person-card__image">
        <img :src="image" alt="" v-if="image">
      </div>

      <div class="person-card__info">
        <div class="person-card__name">
          {{ name }}
        </div>

        <div class="person-card__position">
          {{ position }}
        </div>
      </div>
    </div>

    <h2 class="person-card__title title" v-line-clamp="3" style="word-break: normal;">
      {{ title }}
    </h2>

    <div class="person-card__description" v-line-clamp="2" style="word-break: normal;">
      {{ description }}
    </div>
  </router-link>
</template>

<script>
export default {
  name: 'PersonCard',

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
    description: {
      type: String,
    },
  },
}
</script>
