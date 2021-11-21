<template>
  <div v-if="curricula.length>0">
    <div class="title">Образовательные программы</div>
    <div class="compilations">
      <div class="row">
        <div class="col-50" v-for="(card, key) in curricula" :key="key">
          <PriceCard :id="card.id"
                     :image="card.institution.avatar ? card.institution.avatar.url : ''"
                     :title="card.name"
                     :desc="card.inst_program ? card.inst_program.name : ''"
                     :is-published="card.is_published"
                     :is-edit="false"
                     :curriculum="card"
                     :isFavorited="true"
                     @removedFromFavorites="remove"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserFavoriteCurricula',
  data() {
    return {
      curricula: [],
    };
  },
  mounted() {
    this.getFavoriteCurricula();
  },
  methods: {
    getFavoriteCurricula() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/curricula`,
          {
            params: {
              favorited: 1,
            },
          }).
          then(res => {
            this.curricula = res.data.data.map(item => {
              return {...item, isFavorited: true};
            });
          });
    },
    remove(id) {
      this.curricula = this.curricula.filter(item => item.id !== id);
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