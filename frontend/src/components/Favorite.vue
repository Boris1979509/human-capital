<template>
  <div v-if="$loginStatus && userCanFavorite">
    <div class="container" v-if="isFavorited" @click="removeFromFavorite">
      <div class="icon">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
                d="M2 3.01448C2 1.34963 3.34315 0 5 0H11C12.6569 0 14 1.34963 14 3.01448V13.9866C14 15.6717 12.0601 16.6085 10.7506 15.5558L8 13.3447L5.24939 15.5558C3.93986 16.6085 2 15.6717 2 13.9866V3.01448Z"
                fill="#214EB0"/>
        </svg>
      </div>
      <div class="text">
        В избранном
      </div>
    </div>

    <div class="container" v-else @click="addToFavorite">
      <div class="icon">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
                d="M5 2.00966C4.44772 2.00966 4 2.45953 4 3.01448V13.9866L6.75061 11.7755C7.48105 11.1883 8.51895 11.1883 9.24939 11.7755L12 13.9866V3.01448C12 2.45953 11.5523 2.00966 11 2.00966H5ZM2 3.01448C2 1.34963 3.34315 0 5 0H11C12.6569 0 14 1.34963 14 3.01448V13.9866C14 15.6717 12.0601 16.6085 10.7506 15.5558L8 13.3447L5.24939 15.5558C3.93986 16.6085 2 15.6717 2 13.9866V3.01448Z"
                fill="#214EB0"/>
        </svg>
      </div>
      <div class="text">
        Добавить в избранное
      </div>

    </div>
  </div>
</template>

<script>
export default {
  name: 'Favorite',
  props: {
    initialFavorited: Boolean,
    type: String,
    itemId: Number,
  },
  data() {
    return {
      isFavorited: this.initialFavorited,
    };
  },
  computed: {
    userCanFavorite() {
      return this.$user?.type === 1;
    },
  },

  methods: {
    getRoute() {
      switch (this.type) {
        case 'journal':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.itemId}/favorite`;
        case 'institution':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institution/${this.itemId}/favorite`;
        case 'selection':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/selection/${this.itemId}/favorite`;
        case 'curriculum':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/curriculum/${this.itemId}/favorite`;
        case 'employer':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/employer/${this.itemId}/favorite`;
        case 'vacancy':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/vacancy/${this.itemId}/favorite`;
      }
    },
    removeFromFavorite() {
      this.$http.delete(this.getRoute()).then(() => {
        this.isFavorited = false;
      });
    },
    addToFavorite() {
      this.$http.post(this.getRoute()).then(() => {
        this.isFavorited = true;
      });
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  margin-bottom: 18px;
  cursor: pointer;
}

.icon {
  margin-right: 8px;
}

.text {
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  color: #214EB0;
}
</style>