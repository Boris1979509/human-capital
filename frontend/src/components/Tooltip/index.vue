<template>
  <section
      :class="{
      'hover': activatorHover
    }"
      class="rir-tooltip">
    <slot
        name="activator"
        :openTooltip="openTooltip"
        :hiddenTooltip="hiddenTooltip"
    ></slot>
    <section
        ref="wrapper"
        :style="{
        ...styleWrapper,
        ...styleObject
      }"
        class="rir-tooltip__wrapper"
        v-show="isOpen">
      <slot></slot>
    </section>
    <div
        ref="arrow"
        :style="styleObject"
        v-show="isOpen"
        class="rir-tooltip__wrapper--arrow"></div>
  </section>
</template>

<script>
export default {
  name: 'Tooltip',

  async mounted () {
    await this.checkPositionBubble()
    this.isTooltip = false
  },
  data: () => ({
    isTooltip: true,
    timer: null,
    styleWrapper: {}
  }),
  props: {
    value: {
      type: Boolean,
    },
    color: {
      type: String,
      default: 'alien',
    },
    activatorHover: {
      type: Boolean,
    }
  },
  computed: {
    isOpen () {
      this.checkPositionBubble()
      return this.value || this.isTooltip
    },
    styleObject () {
      return {
        backgroundColor: `var(--color-${this.color})`,
        borderBottomColor: `var(--color-${this.color})`,
        borderTopColor: `var(--color-${this.color})`,
        color: '#fff',
      }
    }
  },
  methods: {
    async checkPositionBubble () {
      await this.$nextTick()
      if (!this.$refs.wrapper) {
        return
      }
      const content = this.$refs.wrapper.closest('.rir-modal__content') || window
      const widthContent = content.clientWidth || content.innerWidth
      const wrapper = this.$refs.wrapper.getBoundingClientRect()
      if (wrapper.x + wrapper.width > widthContent - 20) {
        let positionX = 0
        if (widthContent < window.innerWidth) {
          const blockPosition = content.getBoundingClientRect()
          positionX = (blockPosition.right - wrapper.right) - 20
        } else {
          positionX = widthContent - (wrapper.right) - 20
        }
        this.styleWrapper = {
          left: `${positionX}px`
        }
      }
      if (wrapper.bottom > window.innerHeight) {
        this.styleWrapper = {
          ...this.styleWrapper,
          top: '-30px'
        }
        this.$refs.arrow.classList.add('top')
      } else {
        this.styleWrapper = {
          ...this.styleWrapper,
          top: 'calc(100% + 6px)'
        }
        this.$refs.arrow.classList.remove('top')
      }
    },
    openTooltip () {
      this.timer = setTimeout(() => {
        this.isTooltip = true
        this.checkPositionBubble()
      }, 500)
    },
    hiddenTooltip () {
      this.isTooltip = false
      this.timer && clearTimeout(this.timer)
    }
  }
}
</script>
