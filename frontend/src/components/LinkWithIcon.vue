<template>
  <span class="link" @click="$emit('click')" v-bind="$attrs">
    <slot name="icon"></slot>
    <slot name="text"></slot>
  </span>
</template>

<script>
export default {
  name: 'LinkWithIcon',
};
</script>

<style scoped lang="scss">
.blue {
  color: #214EB0;
}

.red {
  color: #E14761;
}

.link {
  margin-left: 24px;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  text-align: right;
  cursor: pointer;

  .icon {
    margin-right: 6px;
  }

  svg {
    margin-bottom: -2px;
    margin-right: 6px;
  }
}
</style>