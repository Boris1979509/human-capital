<template>
  <div v-if="selections.length>0">
    <div class="title">Подборки</div>
    <div class="compilations">
      <div class="row">
        <div class="col-33" v-for="(card, key) in selections" :key="key">
          <CompilationCard :title="card.title"
                           :description="card.annotation"
                           :route-id="card.id"
                           :isFavorited="true"
                           @removedFromFavorites="remove"/>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserFavoriteSelections',
  data() {
    return {
      selections: [],
    };
  },
  mounted() {
    this.getFavoriteSelections();
  },
  methods: {
    getFavoriteSelections() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/selections`,
          {
            params: {
              favorited: 1,
            },
          }).
          then(res => {
            this.selections = res.data.data;
          });
    },
    remove(id) {
      this.selections = this.selections.filter(item => item.id !== id);
    },
  },
};
</script>

<style scoped>
.title {
  font-style: normal;
  font-weight: 800;
  font-size: 24px;
  line-height: 28px;
  color: #04153E;
  margin-bottom: 24px;
}
</style>