<template>
  <div class="compilations">
    <div class="row">
      <div class="col-33" v-for="(card, key) in selections.slice(0, 6)" :key="key">
        <CompilationCard :title="card.title"
                         :description="card.annotation"
                         :route-id="card.id" />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Compilations',

  computed: {
    selections: function() {
      return this.$selections;
    },
  },
}
</script>
