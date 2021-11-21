<template>
  <a @click="$router.push(isEdit ? `/profile/org/programs/edit/${institution}/${id}` : {name: 'Curriculum', params: {id}})"
     class="price-card">
    <div class="price-card__image" v-if="image">
      <img :src="image" :alt="title">
    </div>
    <FavoriteCardIndicator v-if="isFavorited" :item-id="curriculum.id" type="curriculum"
                           @removedFromFavorites="$emit('removedFromFavorites', $event)"/>

    <h6 class="price-card__title">
      {{ title }}
    </h6>

    <p class="price-card__description" v-if="desc">
      {{ desc }}
    </p>

    <div class="price-card__footer">
      <div class="price-card__price" v-if="price || cardPrice">
        {{ price ? price : cardPrice }} ₽
      </div>

      <div class="price-card__type" :class="!isPublished ? 'price-card__type--not-published' : ''">
        {{ type ? type : cardType }}
      </div>
    </div>
  </a>
</template>

<script>
import FavoriteCardIndicator from '@/components/UserFavorites/FavoriteCardIndicator';

export default {
  name: 'PriceCard',
  components: {FavoriteCardIndicator},

  props: {
    id: {
      type: [String, Number],
      default: () => '',
    },
    institution: {
      type: [String, Number],
      default: () => '',
    },
    image: {
      type: String,
      default: () => '',
    },
    title: {
      type: String,
      default: () => '',
    },
    desc: {
      type: String,
    },
    price: {
      type: [String, Number],
    },
    type: {
      type: [Number, String],
    },
    isPublished: {
      type: Boolean,
    },
    isEdit: {
      type: Boolean,
      default: () => true,
    },
    isFavorited: {
      type: Boolean,
      default: false,
    },
    curriculum: Object,
  },

  computed: {
    cardPrice() {
      if (!this.curriculum) {
        return '';
      }
      let learningOptions = this.curriculum.learning_options;
      return Math.max.apply(Math, learningOptions.map(max => max.cost));
    },
    cardType() {
      if (!this.curriculum) {
        return '';
      }
      let learningOptions = this.curriculum?.learning_options;
      return learningOptions.reduce(
          (max, option) => max.cost > option.cost ? max : option,
      ).edu_form.name;
    },
  },
};
</script>
<style>
.price-card {
  cursor: pointer;
  position: relative;
}
</style>