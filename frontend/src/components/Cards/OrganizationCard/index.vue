<template>
  <a @click="$router.push(`/institutions/${organization.id}`)" class="organization-card">
    <FavoriteCardIndicator v-if="organization.isFavorited" :item-id="organization.id" type="institution"
                           @removedFromFavorites="$emit('removedFromFavorites', $event)"/>
    <div class="organization-card__avatar">
      <img :src="organizationAvatar" :alt="organization.full_name" v-if="organizationAvatar">
    </div>
    <div class="organization-card__name">{{ organization.short_name }}</div>
    <div class="organization-card__type">{{ organizationType }}</div>
    <div class="organization-card__curricula_count">{{ countWithWord }}</div>
  </a>
</template>

<script>
import FavoriteCardIndicator from '@/components/UserFavorites/FavoriteCardIndicator';

export default {
  name: 'OrganizationCard',
  components: {FavoriteCardIndicator},

  props: {
    organization: Object,
  },

  data() {
    return {
      curriculaWords: ['программа', 'программы', 'программ'],
    };
  },

  computed: {
    organizationAvatar() {
      return this.organization.avatar?.thumb;
    },
    organizationType() {
      return this.organization.type?.name;
    },
    countWithWord() {
      return this.organization.count_curricula + ' ' +
          this.$num2str(this.organization.count_curricula, this.curriculaWords);
    },
  },
};
</script>
<style>
.organization-card {
  cursor: pointer;
  position: relative;
}
</style>
