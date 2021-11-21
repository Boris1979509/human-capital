<template>
  <img :src="require(`@/assets/svg/${filename}.svg`)" alt="" />
</template>

<script>
export default {
  name: 'IconCustom',
  props: {
    filename: String,
  },
  data() {
    return {};
  },
};
</script>

<style lang="scss" scoped>
img {
  display: inline-block;
}
</style>
