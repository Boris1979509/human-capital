<template>
  <div>
    <Button class="avatar" @click.native="$refs.addAvatar.openModal()">
      <CircleProgress :progress="progress"/>
      <img :src="avatar ? avatar : require('@/assets/svg/user.svg')" alt="">
    </Button>

    <modal ref="addAvatar" class="avatar-modal" :body-width="600">
      <template v-slot:body>
        <Cropper
            v-if="avatarInProgress"
            class="cropper"
            ref="cropper"
            :src="avatarInProgress"
            stencil-component="circle-stencil"
            :default-size="defaultSize"
        />
      </template>

      <template v-slot:footer>
        <div class="avatar-modal__buttons">
          <div class="avatar-modal__upload-btn">
            <label for="avatar" class="btn btn--upload">
              <Icon xlink="image" viewport="0 0 16 16"/>
              Загрузить фотографию
            </label>
            <input type="file" id="avatar" name="file" accept="image/jpg, image/png" @change="addImage">
          </div>

          <Button class="btn--delete" @click.native="deleteImage">
            <Icon xlink="delete" viewport="0 0 16 16"/>
            Удалить фотографию
          </Button>
        </div>

        <Button class="btn btn--blue avatar-modal__save-btn" :disabled="isDisabled" @click.native="uploadImage">
          Сохранить
        </Button>
      </template>
    </modal>
  </div>
</template>

<script>
import {Cropper} from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

export default {
  name: 'Avatar',

  components: {
    Cropper,
  },

  props: {
    id: {
      type: [String, Number],
    },
    progress: {
      type: [String, Number],
      default: () => 0,
    },
    type: {
      type: String,
    },
  },

  computed: {
    currentAvatar: function() {
      if (this.type === 'user') return this.$getUserAvatar;
      if (this.type === 'organization') return this.$getOrgAvatar;
      if (this.type === 'employer') return this.$getEmployerAvatar;
      return null;
    },
  },

  watch: {
    currentAvatar: function(val) {
      this.avatar = this.avatarInProgress = val;
    },
  },

  mounted() {
    this.avatar = this.avatarInProgress = this.currentAvatar;
  },

  data: function() {
    return {
      avatar: null,
      avatarInProgress: null,
      imageType: null,

      isDisabled: false,
    }
  },

  methods: {
    defaultSize({imageSize, visibleArea}) {
      return {
        width: (visibleArea || imageSize).width,
        height: (visibleArea || imageSize).height,
      };
    },
    uploadImage: function() {
      this.isDisabled = true;
      const {canvas} = this.$refs.cropper.getResult();

      if (canvas) {
        if (this.type === 'user') {
          canvas.toBlob(blob => {
            this.$uploadAvatar(blob, `api/user/avatar`, this.id, this.imageType)
                .then(result => {
                  this.$store.commit('SET_USER_AVATAR', result);
                  this.avatar = result;
                  this.isDisabled = false;
                  this.$refs.addAvatar.closeModal();
                });
          }, this.imageType);
        }

        if (this.type === 'organization') {
          canvas.toBlob(blob => {
            this.$uploadAvatar(blob, `api/lk/institutions/${this.id}/avatar`, null, this.imageType)
                .then(result => {
                  this.$store.commit('SET_ORG_AVATAR', result);
                  this.avatar = result;
                  this.isDisabled = false;
                  this.$refs.addAvatar.closeModal();
                });
          }, this.imageType);
        }

        if (this.type === 'employer') {
          canvas.toBlob(blob => {
            this.$uploadAvatar(blob, `api/lk/employer/${this.id}/avatar`, null, this.imageType)
                .then(result => {
                  this.$store.commit('SET_EMPLOYER_AVATAR', result);
                  this.avatar = result;
                  this.isDisabled = false;
                  this.$refs.addAvatar.closeModal();
                });
          }, this.imageType);
        }
      }
    },

    deleteImage: function() {
      if (this.type === 'user') {
        this.$http.delete(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/avatar`)
            .then(() => {
              this.$store.commit('SET_USER_AVATAR', null);
              this.avatar = null;
              this.$refs.addAvatar.closeModal();
            });
      }

      if (this.type === 'organization') {
        this.$http.delete(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.id}/avatar`)
            .then(() => {
              this.$store.commit('SET_ORG_AVATAR', null);
              this.avatar = null;
              this.$refs.addAvatar.closeModal();
            });
      }

      if (this.type === 'employer') {
        this.$http.delete(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/employer/avatarDelete`)
            .then(() => {
              this.$store.commit('SET_EMPLOYER_AVATAR', null);
              this.avatar = null;
              this.$refs.addAvatar.closeModal();
            });
      }
    },

    addImage(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.avatarInProgress = e.target.result;
          this.imageType = this.$getMimeType(e.target.result, input.files[0].type);
        };
        reader.readAsDataURL(input.files[0]);
      }
    },
  },
}
</script>

<style>
.cropper {
  height: 60vh;
}

.vue-advanced-cropper__image {
  border-radius: 8px;
}

.vue-advanced-cropper__background {
  background: #fff;
}

.vue-advanced-cropper__foreground {
  background: #fff;

  opacity: .4;
}

.vue-simple-handler {
  background: #3D75E4;
}

.vue-simple-line {
  border-color: #3D75E4;
}
</style>
