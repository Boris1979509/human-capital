<template>
  <transition name="fade">
    <div class="modal" v-if="value">
      <div class="modal__backdrop" @click="$emit('input', false)"/>

      <div class="modal__dialog">
        <div class="container">
          <div class="modal__close" @click="$emit('input', false)">
            <svg width="32" height="32" viewBox="0 0 32 32" fill="none" xmlns="http://www.w3.org/2000/svg">
              <g clip-path="url(#clip0)">
                <path
                    d="M1.70711 0.292893C1.31658 -0.0976311 0.683418 -0.0976311 0.292893 0.292893C-0.0976311 0.683417 -0.0976311 1.31658 0.292893 1.70711L14.5858 16L0.292893 30.2929C-0.0976311 30.6834 -0.0976311 31.3166 0.292893 31.7071C0.683418 32.0976 1.31658 32.0976 1.70711 31.7071L16 17.4142L30.2929 31.7071C30.6834 32.0976 31.3166 32.0976 31.7071 31.7071C32.0976 31.3166 32.0976 30.6834 31.7071 30.2929L17.4142 16L31.7071 1.70711C32.0976 1.31658 32.0976 0.683417 31.7071 0.292893C31.3166 -0.0976309 30.6834 -0.0976309 30.2929 0.292893L16 14.5858L1.70711 0.292893Z"
                    fill="#3D75E4"/>
              </g>
              <defs>
                <clipPath id="clip0">
                  <rect width="32" height="32" fill="white"/>
                </clipPath>
              </defs>
            </svg>
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
    </div>
  </transition>
</template>

<script>
export default {
  name: 'Modal',
  props: {
    value: Boolean,
  },
  methods: {},
};
</script>

<style lang="scss" scoped>
.container {
  max-width: 618px;
  margin: 0 auto;
}

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
    width: 1024px;
    background-color: #ffffff;
    border-radius: 5px;
    margin: 50px auto;
    display: flex;
    flex-direction: column;
    z-index: 2;
    padding: 35px;
    box-sizing: content-box;
  }

  &__close {
    cursor: pointer;
    position: absolute;
    width: 32px;
    right: 16px;
    top: 16px;
  }

  &__header {
    display: flex;
    align-items: flex-start;
    justify-content: space-between;
  }

  &__body {
    display: flex;
    flex-direction: column;
    align-items: stretch;
    margin-bottom: 32px;
  }

  &__footer {
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
