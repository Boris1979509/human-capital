<template>
  <div class="favorite" @click.stop="removeFromFavorites">
    <div class="icon">
      <svg width="12" height="16" viewBox="0 0 12 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M0 3.01448C0 1.34963 1.34315 0 3 0H9C10.6569 0 12 1.34963 12 3.01448V13.9866C12 15.6717 10.0601 16.6085 8.75061 15.5558L6 13.3447L3.24939 15.5558C1.93986 16.6085 0 15.6717 0 13.9866V3.01448Z"
              fill="white"/>
      </svg>
    </div>
  </div>
</template>

<script>
export default {
  name: 'FavoriteCardIndicator',
  props: {
    itemId: Number,
    type: String,
  },
  data() {
    return {
      isFavorited: true,
    };
  },
  methods: {
    removeFromFavorites() {
      this.$http.delete(this.getRoute()).then(() => {
        this.$emit('removedFromFavorites', this.itemId);
      });
    },
    getRoute() {
      switch (this.type) {
        case 'journal':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.itemId}/favorite`;
        case 'institution':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions/${this.itemId}/favorite`;
        case 'selection':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/selections/${this.itemId}/favorite`;
        case 'curriculum':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/curricula/${this.itemId}/favorite`;
      }
    },
  },
};
</script>

<style lang="scss" scoped>
.favorite {
  position: absolute;
  top: 0;
  z-index: 100;
  right: 0;
  color: white;
  border-bottom-left-radius: 16px;
  border-top-right-radius: 16px;
  width: 40px;
  height: 40px;
  background: #04153E;
  opacity: 0.32;

  &:hover {
    opacity: 1;
  }
}

.icon {
  position: absolute;
  top: 50%;
  left: 50%;
  transform: translate(-50%, -50%);

  &:hover {
    opacity: 1;
  }
}
</style>