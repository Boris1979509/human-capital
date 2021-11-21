<template>
  <i
    v-html="iconHtml"
    class="rir-icon"
  ></i>
</template>

<script>
export default {
  name: 'rir-icon',
  mounted () {
    this.setAttributeSvg()
  },
  watch: {
    $props: {
      deep: true,
      handler () {
        this.setAttributeSvg()
      }
    }
  },
  props: {
    icon: {
      type: String,
      required: true
    },
    fill: {
      type: String,
      default: 'rocky'
    },
    size: {
      type: [String, Number],
      default: 16
    }
  },
  computed: {
    iconHtml () {
      return require(`!html-loader!./svg/${this.size}/${this.icon}_${this.size}.svg`)
    }
  },
  methods: {
    setAttributeSvg () {
      this.$nextTick(() => {
        const firstElement = this.$el.firstElementChild
        if (!firstElement) return
        if (this.$el.firstElementChild.nodeName === 'svg') {
          const svgElement = this.$el.firstElementChild
          this.recursivelyChangeFill(svgElement)
          svgElement.setAttribute('height', `${this.size}px`)
          svgElement.setAttribute('width', `${this.size}px`)
          svgElement.classList.add('svg-class')
        }
      })
    },
    recursivelyChangeFill (el) {
      if (!el) {
        return
      }
      el.setAttribute('fill', `var(--rir-${this.fill})`);
      [].forEach.call(el.children, child => {
        this.recursivelyChangeFill(child)
      })
    }
  }
}
</script>
