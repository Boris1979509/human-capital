<template>
  <div class="filter_panel">
    <div
        v-for="item in items"
        class="item"
        :class="{ selected: value === item.id }"
        @click="$emit('input', item.id)"
        :key="item.id"
    >
      {{ item.name }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'FilterTabs',
  props: {
    value: {
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
  },
};
</script>

<style scoped lang="scss">
.filter_panel {
  display: flex;
  margin-bottom: 32px;

  .item {
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 20px;
    letter-spacing: 0em;
    text-align: left;
    margin-left: 20px;
    cursor: pointer;

    &:first-child {
      margin-left: 0;
    }

    &.selected:after {
      content: '';
      background: var(--main-color);
      display: block;
      height: 4px;
      border-radius: 2px;
      margin-top: 8px;
    }
  }
}
</style>