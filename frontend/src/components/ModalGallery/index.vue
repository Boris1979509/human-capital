<template>
  <div class="modal-gallery">
    <div class="modal-gallery__photo-row" v-if="array && array.length">
      <div
        class="modal-gallery__photo-row__item"
        v-for="(item, idx) in array.slice(0, 4)"
        :key="idx"
        :data-photos="array.length - 4"
        @click="
          openModalGallery();
          currentSlide = idx + 1;
        "
        :class="{
          hasMorePhotos: array.length > 4,
        }"
        :style="[
          {
            background: `url(${item.url})`,
          },
        ]"
      ></div>
    </div>
    <transition name="fade">
      <div class="modal" v-if="show">
        <div class="modal__backdrop" @click="closeModalGallery()" />

        <div
          class="modal__dialog"
          :style="[{ width: `${bodyWidth}px` }, { height: `${bodyHeight}px` }]"
        >
          <div class="modal__header">
            <!-- <slot name="header" /> -->
            <div
              class="modal__close"
              v-if="isDefaultClose"
              @click="closeModalGallery()"
            >
              <svg
                width="20"
                height="20"
                viewBox="0 0 20 20"
                fill="none"
                xmlns="http://www.w3.org/2000/svg"
              >
                <path
                  d="M0.292893 18.2929C-0.0976311 18.6834 -0.0976311 19.3166 0.292893 19.7071C0.683418 20.0976 1.31658 20.0976 1.70711 19.7071L10 11.4142L18.2929 19.7071C18.6834 20.0976 19.3166 20.0976 19.7071 19.7071C20.0976 19.3166 20.0976 18.6834 19.7071 18.2929L11.4142 10L19.7071 1.70711C20.0976 1.31658 20.0976 0.683418 19.7071 0.292894C19.3166 -0.0976315 18.6834 -0.0976314 18.2929 0.292894L10 8.58579L1.70711 0.292894C1.31658 -0.0976303 0.683418 -0.0976303 0.292893 0.292894C-0.097631 0.683418 -0.097631 1.31658 0.292893 1.70711L8.58579 10L0.292893 18.2929Z"
                  fill="white"
                />
              </svg>
            </div>
          </div>

          <div class="modal__body">
            <!-- <slot name="body" /> -->
            <PhotoSlider :array="array" :currentSlide.sync="currentSlide" />
          </div>

          <div class="modal__footer">
            <!-- <slot name="footer" /> -->
            <div class="info-block">
              <div class="info-block__left">
                <div class="image-name">
                  {{ currentSlide && array[currentSlide - 1]['file_name'] }}
                </div>
                <div class="image-current">
                  {{ currentSlide }} из {{ array.length }}
                </div>
              </div>
              <div class="info-block__right">
                <div class="image-size">
                  {{
                    array[currentSlide - 1] &&
                      array[currentSlide - 1]['size'] &&
                      Math.floor(array[currentSlide - 1]['size'] / 1024)
                  }}
                  Кб
                </div>
                <a
                  class="image-download"
                  @click.prevent="
                    (e) =>
                      downloadWithAxios(
                        array[currentSlide - 1]['url'],
                        array[currentSlide - 1]['file_name'],
                        e.target
                      )
                  "
                >
                  <IconCustom filename="components/ModalGallery/download" />
                  Скачать
                </a>
              </div>
            </div>
          </div>
        </div>
      </div>
    </transition>
  </div>
</template>

<script>
import axios from 'axios';
export default {
  name: 'ModalGallery',

  props: {
    array: {
      type: Array,
      default: () => {
        [];
      },
    },
    isDefaultClose: {
      type: Boolean,
      default: () => true,
    },
    bodyWidth: {
      type: [String, Number],
      default: () => 400,
    },
    bodyHeight: {
      type: [String, Number],
      default: () => 400,
    },
  },

  data() {
    return {
      currentSlide: null,
      show: false,
    };
  },

  methods: {
    forceFileDownload(response, title, target) {
      const url = window.URL.createObjectURL(new Blob([response.data]));
      const link = target;
      link.href = url;
      link.setAttribute('download', title);
      link.click();
    },
    downloadWithAxios(url, title, target) {
      axios({
        method: 'get',
        url,
        responseType: 'arraybuffer',
      })
        .then((response) => {
          this.forceFileDownload(response, title, target);
        });
    },
    closeModalGallery() {
      this.show = false;
      document.querySelector('body').classList.remove('overflow-hidden');
    },
    openModalGallery() {
      this.show = true;
      document.querySelector('body').classList.add('overflow-hidden');
    },
  },
};
</script>

<style lang="scss" scoped>
.modal-gallery {
  height: 82px;
  margin-top: 32px;
  &__photo-row {
    display: grid;
    grid-template-columns: repeat(4, 1fr);
    grid-gap: 30px;
    height: 100%;
    &__item {
      border-radius: 8px;
      cursor: pointer;
      background-size: cover !important;
      background-repeat: no-repeat !important;
      background-position: center !important;
      &:last-child {
        position: relative;
        &.hasMorePhotos:before {
          content: '+ ' attr(data-photos);
          display: flex;
          justify-content: center;
          align-items: center;
          height: 100%;
          color: var(--rir-amelie);
          background: rgba(4, 21, 62, 0.16);
          font-size: 16px;
          font-weight: 500;
          line-height: 20px;
        }
      }
    }
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
      width: 400px;
      background-color: #ffffff;
      border-radius: 16px;
      margin: 50px auto;
      display: flex;
      flex-direction: column;
      z-index: 2;
      padding: 0;
      box-sizing: content-box;
      @media screen and (max-width: 992px) {
        width: 90%;
      }
    }
    &__close {
      position: absolute;
      top: -25px;
      right: -25px;
    }
    &__header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      // padding: 20px 20px 10px;
      // margin-bottom: 24px;
      .modal__close {
        cursor: pointer;
      }
    }
    &__body {
      // padding: 10px 20px 10px;
      // overflow: auto;
      display: flex;
      flex-direction: column;
      align-items: stretch;
      // margin-bottom: 32px;
      height: 100%;
      // outline: 1px solid;
      ::v-deep {
        .slider_wrapper {
          border-radius: 16px 16px 0 0;
          height: 100%;
          overflow: visible;
          .slider_arr_left {
            left: -28px;
          }
          .slider_arr_right {
            right: -28px;
          }
          .slider_counter {
            display: none;
          }
          .slider {
            height: 100%;
            border-radius: 16px 16px 0 0;
            &__body {
              height: 100%;
            }

            &__slide {
              height: 100%;
            }
          }
        }
      }
    }
    &__footer {
      height: 62px;
      padding: 0 20px;
      .info-block {
        display: flex;
        justify-content: space-between;
        height: 100%;
      }
      .info-block {
        &__left {
          display: flex;
          align-items: center;
          .image-name {
            max-width: 160px;
            overflow: hidden;
            text-overflow: ellipsis;
            font-size: 16px;
            font-weight: 700;
            line-height: 20px;
            margin-right: 12px;
            color: var(--main-color-dark);
          }
          .image-current {
            font-size: 13px;
            font-weight: 400;
            line-height: 16px;
            color: var(--main-color-dark);
            opacity: 0.48;
          }
        }
        &__right {
          display: flex;
          align-items: center;
          .image-size {
            font-size: 13px;
            font-weight: 400;
            line-height: 16px;
            color: var(--main-color-dark);
            opacity: 0.48;
            margin-right: 16px;
          }
          .image-download {
            display: flex;
            align-items: center;
            font-size: 16px;
            font-weight: 500;
            line-height: 20px;
            color: var(--main-color);
            cursor: pointer;
            display: none;
            img {
              margin-right: 6px;
            }
          }
        }
      }
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
