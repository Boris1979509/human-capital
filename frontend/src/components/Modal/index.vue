<template>
  <transition name="fade">
    <div class="modal" v-if="show">
      <div class="modal__backdrop" @click="closeModal()"/>

      <div class="modal__dialog" :style="`width: ${bodyWidth}px;`">
          <div class="modal__close" v-if="isDefaultClose" @click="closeModal()">
            <img :src="require('@/assets/img/close_32.jpg')" alt="">
          </div>
          <div class="modal__header">
            <slot name="header"/>

          </div>

          <div class="modal__body">
            <slot name="body"/>
          </div>

          <div class="modal__footer">
            <slot name="footer"/>
          </div>
        </div>
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Modal',

  props: {
    isDefaultClose: {
      type: Boolean,
      default: () => true,
    },
    bodyWidth: {
      type: [String, Number],
      default: () => 400,
    },
  },

  data() {
    return {
      show: false,
    };
  },

  methods: {
    closeModal() {
      this.show = false;
      document.querySelector('body').classList.remove('overflow-hidden');
    },
    openModal() {
      this.show = true;
      document.querySelector('body').classList.add('overflow-hidden');
    },
  },
};
</script>

<style lang="scss" scoped>
.modal {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
  z-index: 9;
  overflow-x: hidden;
  overflow-y: auto;

  &__backdrop {
    position: fixed;
    top: 0;
    right: 0;
    bottom: 0;
    left: 0;
    background-color: rgba(0, 0, 0, 0.3);
    z-index: 1;
  }

  &__dialog {
    position: relative;
    width: 400px;
    padding: 100px 200px;
    background-color: #ffffff;
    border-radius: 5px;
    margin: 50px auto;
    display: flex;
    flex-direction: column;
    z-index: 2;
    box-sizing: content-box;
    @media screen and (max-width: 992px) {
      width: 90%;
    }
  }

  &__close {
    // width: 30px;
    // height: 30px;
    position: absolute;
    right: 16px;
    top: 16px;
    cursor: pointer;
  }

  &__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
    padding: 20px 20px 10px;
    margin-bottom: 24px;

  }

  &__body {
    // padding: 10px 20px 10px;
    // overflow: auto;
    display: flex;
    flex-direction: column;
    align-items: stretch;
    margin-bottom: 32px;
  }

  &__footer {
    // padding: 10px 20px 20px;
    .btn-wrapper {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      grid-gap: 50px;
    }
  }
}

.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.2s;
}

.fade-enter,
.fade-leave-to {
  opacity: 0;
}
</style>
