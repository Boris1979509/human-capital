<template>
  <div
    ref="spinner"
    :style="styleRing"
    class="loading-ring__spinner"></div>
</template>

<script>
export default {
  name: 'loading-ring',
  mounted () {
    const precision = 100
    const radius = 44
    const c = [...Array(precision)].map((_, i) => {
      const a = -i / (precision - 1) * Math.PI * 2
      const x = Math.cos(a) * radius + 50
      const y = Math.sin(a) * radius + 50
      return `${x}% ${y}%`
    })
    this.$refs.spinner.style.clipPath =
      `polygon(100% 50%, 100% 100%, 0 100%, 0 0, 100% 0, 100% 50%, ${c.join(',')})`
  },
  props: {
    size: {
      type: Number,
      default: 72
    },
    color: {
      type: String,
      default: '#3D75E4'
    }
  },
  computed: {
    styleRing () {
      return {
        width: `${this.size}px`,
        height: `${this.size}px`,
        background: `conic-gradient(from 90deg at 50% 50%, rgba(61, 117, 228, 0) 0deg, ${this.color} 360deg)`
      }
    }
  }
}
</script>

<style lang="scss" scoped>
.loading-ring{
  padding: 40px;
  display: flex;
  align-items: center;
  justify-content: center;
  flex-direction: column;
  position: relative;
  &__spinner{
    position: relative;
    //width: 72px;
    //height: 72px;
    //background: conic-gradient(from 90deg at 50% 50%, rgba(61, 117, 228, 0) 0deg, #3D75E4 360deg);
    border-radius: 50%;
    animation:spin 2s linear infinite;
    display: flex;
    justify-content: center;
    align-items: center;

    &:after {
      content: '';

      display: block;

      width: 60px;
      height: 60px;

      background: #fff;

      border-radius: 50%;

      position: absolute;
      top: 6px;
      right: 6px;
      left: 6px;
      bottom: 6px;

    }
  }
  &__text{
    margin-top: 12px;
    font-size: 16px;
    line-height: 20px;
    color: var(--main-color);
  }
}


@keyframes spin { 100% { -webkit-transform: rotate(360deg); transform:rotate(360deg); } }
</style>
