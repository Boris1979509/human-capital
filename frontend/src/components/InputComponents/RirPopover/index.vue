<template>
  <section
    class="rir-popover"
    :id="`${uId}-popover`">
    <div
      ref="activator"
      class="rir-popover__activator">
      <slot
        name="activator"
        :on="on"
      ></slot>
    </div>
    <div
      :id="uId"
      v-click-outside="clickOutside"
      :style="styleContent"
      :class="[contentClass]"
      ref="content"
      class="rir-popover__content"
      v-if="isOpen">
      <slot></slot>
    </div>
  </section>
</template>

<script>
import ClickOutside from '@/utils/directives/ClickOutside'
import uuid from '@/utils/mixins/uuid'
export default {
  name: 'rir-popover',
  beforeDestroy () {
    const el = document.getElementById(this.uId)
    el && el.remove()
  },
  mixins: [uuid],
  directives: { ClickOutside },
  data: (vm) => ({
    uId: vm.uuIdv4(),
    isContent: false,
    styleContent: null,
    on: {
      click: vm.changeContent
    }
  }),
  props: {
    value: {
      type: Boolean
    },
    contentClass: {
      type: String
    },
    disabled: {
      type: Boolean
    }
  },
  watch: {
    isContent: function (val) {
      val && this.getContent()
    },
    value: function (val) {
      this.isContent = val
    }
  },
  computed: {
    isOpen () {
      return this.value || this.isContent
    }
  },
  methods: {
    async getContent () {
      await this.$nextTick()
      const content = this.$refs.content.$el || this.$refs.content
      document.body.insertBefore(content, document.body.firstChild)
      this.$refs.content.style.maxWidth = `${this.$el.clientWidth}px`

      const activator = this.$refs.activator.firstElementChild.getBoundingClientRect()
      const contentData = content.getBoundingClientRect()

      let top = activator.y + window.scrollY + activator.height + 8
      if (window.innerHeight < top - window.scrollY + contentData.height) {
        top -= top + contentData.height - window.innerHeight - window.scrollY
      }
      let left = activator.x
      if (window.innerWidth < left + contentData.width) {
        left -= left + contentData.width - window.innerWidth
      }

      this.styleContent = {
        top: `${top}px`,
        left: `${left}px`
      }
    },
    changeContent () {
      if (this.disabled) return
      this.isContent = !this.isContent
      const content = this.$refs.activator.closest('.rir-cards-block__content')
      if (content) {
        content.onscroll = () => {
          this.isContent = false
          this.$emit('input', this.isContent)
          content.onscroll = () => {}
        }
      }
      this.$emit('input', this.isContent)
    },
    clickOutside (el) {
      const path = el.path || (el.composedPath && el.composedPath())
      const isElement = path.some(e => {
        return !!e.classList && e.id === `${this.uId}-popover`
      })
      if (!isElement) {
        const content = this.$refs.activator.closest('.rir-cards-block__content')
        if (content) {
          content.onscroll = () => {}
        }
        this.isContent = false
        this.$emit('input', this.isContent)
      }
    }
  }
}
</script>
