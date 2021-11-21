<template>
  <nav class="tabs">
    <router-link :to="tab.route"
                 class="tabs__item"
                 v-for="tab in tabs"
                 :key="tab.id">
      <div class="tabs__item-inner">
        {{ tab.name }}
        <span class="tabs__counter" v-if="tab.counter">{{ tab.counter }}</span>
      </div>
    </router-link>
  </nav>
</template>

<script>
export default {
  name: 'Tabs',

  props: {
    tabs: {
      type: [Array, Object],
      default: () => [],
    },
  }
}
</script>
