<template>
  <a @click="$router.push(`/selections/${routeId}`)" :title="title" class="compilation"
     :class="colors[$getRandomColor(colors.length)]">

      <span class="compilation__icon"></span>

    <div class="compilation__container">
      <FavoriteCardIndicator v-if="isFavorited" :item-id="routeId"
                             @removedFromFavorites="$emit('removedFromFavorites', $event)"
                             type="selection"/>
      <div class="compilation__title">
        {{ title }}
      </div>

      <div class="compilation__description" v-if="description">
        {{ description }}
      </div>
    </div>
  </a>
</template>

<script>
import FavoriteCardIndicator from '@/components/UserFavorites/FavoriteCardIndicator';

export default {
  name: 'CompilationCard',
  components: {FavoriteCardIndicator},

  props: {
    title: {
      type: String,
    },
    description: {
      type: String,
    },
    routeId: {
      type: [Number, String],
    },
    isFavorited: {
      type: Boolean,
      default: false,
    },
  },

  data: function() {
    return {
      colors: ['compilation--orange', 'compilation--blue', 'compilation--red', 'compilation--green'],
    };
  },
};
</script>
<style>
.compilation {
  cursor: pointer;
}
</style>
