<template>
  <div v-if="institutions.length>0">
    <div class="title">Образовательные организации</div>
    <div class="compilations">
      <div class="row">
        <div class="col-33" v-for="(institution, key) in institutions" :key="key">
          <OrganizationCard
              :organization="institution"
              @removedFromFavorites="remove"
          />
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'UserFavoriteInstitutions',
  data() {
    return {
      institutions: [],
    };
  },
  mounted() {
    this.getFavoriteInstitutions();
  },
  methods: {
    getFavoriteInstitutions() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions`,
          {
            params: {
              favorited: 1,
            },
          }).
          then(res => {
            this.institutions = res.data.data.map(item => {
              return {...item, isFavorited: true};
            });
          });
    },
    remove(id) {
      this.institutions = this.institutions.filter(item => item.id !== id);
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