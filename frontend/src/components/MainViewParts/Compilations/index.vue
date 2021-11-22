<template>
  <div class="compilations">
    <div class="row">
      <div class="col-33" v-for="(card, key) in selections.slice(0, 6)" :key="key">
        <CompilationCard :title="card.title"
                         :description="card.annotation"
                         :route-id="card.id" />



          <PredictionCard/> <!-- Не знаю в какой конкретно момент нужно подключать этот компонент -->

      </div>
    </div>
  </div>
</template>

<script>

import PredictionCard from '@/components/Cards/PredictionCard'

export default {

    components: {
        PredictionCard
    },

  name: 'Compilations',

  computed: {
    selections: function() {
      return this.$selections;
    },
  },
}
</script>
