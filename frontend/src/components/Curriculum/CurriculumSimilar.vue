<template>
  <div class="similar">
    <h1>Похожие программы</h1>
    <div class="row">
      <div class="col-33" v-for="card in similarCurricula" :key="card.id">
        <PriceCard :id="card.id"
                   :image="card.institution.avatar ? card.institution.avatar.url : ''"
                   :title="card.name"
                   :desc="card.inst_program ? card.inst_program.name : ''"
                   :price="getCardPrice(card)"
                   :type="card.learning_options ? getCardType(card) : ''"
                   :is-published="card.is_published"
                   :is-edit="false"
        />
      </div>
    </div>
  </div>
</template>

<script>

export default {
  name: 'CurriculumSimilar',
  props: {
    curriculum: Object,
  },
  data() {
    return {
      similarCurricula: [],
    };
  },
  mounted() {
    this.getSimilarCurricula();
  },
  methods: {
    getSimilarCurricula() {
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/curricula/${this.curriculum.id}/similar?order_by=-publish_at&limit=3`).
          then(({data}) => {
            this.similarCurricula = data.data;
          });
    },
    getCardPrice(curriculum) {
      return Math.max.apply(Math, curriculum.learning_options.map(max => max.cost));
    },
    getCardType(curriculum) {
      return curriculum.learning_options.reduce((max, option) => max.cost > option.cost ? max : option).edu_form.name;
    },
  },
};
</script>

<style scoped lang="scss">
.similar {
  padding-top: 72px;
}

h1 {
  font-weight: 800;
  font-size: 40px;
  line-height: 44px;
}
</style>