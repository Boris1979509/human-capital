<template>
  <div>
    <div class="upload-container" :style="`margin-bottom: ${margin}px`">
      <div class="dropbox">
        <input type="file"
               name="files[]"
               accept="image/jpg, image/png"
               class="dropbox__input-file"
               @change="addImage">

        <div class="dropbox__main-text">
          <span>
            <Icon xlink="attach" viewport="0 0 16 16"/>
            {{ description }}
          </span>

          <span>
            Поддерживаемые форматы: jpg, png Файл не более 10 МБайт
          </span>
        </div>
      </div>

      <div class="upload" v-if="uploadedFiles.length > 0">
        <div
            class="upload__item-container"
            v-for="(item, key) in uploadedFiles"
            :key="key"
        >
          <div class="upload__item">
            <div class="upload__item-icon">
              <img :src="item.url" :alt="item.title" class="upload__image"/>
            </div>

            <div class="upload__item-info">
            <span>
              {{ item.file_name }}
            </span>

              <span>
              {{ item.size / 1000 }} КБайт
            </span>
            </div>

            <div class="upload__item-delete">
              <Button @click.native="removeFile(item.id)">
                <Icon xlink="delete" viewport="0 0 16 16"/>
              </Button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <modal ref="addPhoto" class="avatar-modal" :body-width="600">
      <template v-slot:body>
        <Cropper
            v-if="photoInProgress"
            class="cropper"
            ref="cropper"
            :src="photoInProgress"
            :stencil-component="stencilComponent"
            :default-size="defaultSize"
        />
      </template>

      <template v-slot:footer>
        <Button class="btn btn--blue avatar-modal__save-btn"
                :disabled="isDisabled"
                @click.native="uploadImage">
          Загрузить
        </Button>
      </template>
    </modal>
  </div>
</template>

<script>
import {Cropper} from 'vue-advanced-cropper';
import 'vue-advanced-cropper/dist/style.css';

const STATUS_INITIAL = 0,
    STATUS_SAVING = 1,
    STATUS_SUCCESS = 2,
    STATUS_FAILED = 3;

export default {
  name: 'UploadWithCrop',

  components: {
    Cropper,
  },

  props: {
    margin: {
      type: Number,
      default: () => 20,
    },
    preview: {
      type: Array,
      default: () => [],
    },
    description: {
      type: String,
      default: () => 'Загрузить фотографию',
    },
    stencilComponent: {
      type: String,
      default: () => 'circle-stencil',
    },
  },

  computed: {
    isSaving() {
      return this.currentStatus === STATUS_SAVING;
    },
    isSuccess() {
      return this.currentStatus === STATUS_SUCCESS;
    },
    isFailed() {
      return this.currentStatus === STATUS_FAILED;
    },
  },

  mounted() {
    this.reset();
  },

  data() {
    return {
      uploadedFiles: [],
      uploadedFilesIds: [],
      uploadError: null,
      currentStatus: null,

      photoInProgress: null,
      imageType: null,
      isDisabled: false,
    };
  },

  watch: {
    uploadedFiles(val) {
      this.uploadedFilesIds = val.filter((item) => item !== null).map((item) => item.id);
      this.$emit('update:photos', this.uploadedFilesIds[0]);
    },
    preview: {
      handler: function(val) {
        if (val) {
          this.uploadedFiles = [...val.filter((item) => item !== null)];
        }
      },
      immediate: true,
    },
  },

  methods: {
    reset() {
      this.currentStatus = STATUS_INITIAL;
      this.uploadError = null;
    },

    removeFile: function(id) {
      this.uploadedFiles = this.uploadedFiles.filter((item) => item.id !== id);
    },

    defaultSize({imageSize, visibleArea}) {
      return {
        width: (visibleArea || imageSize).width,
        height: (visibleArea || imageSize).height,
      };
    },

    save(formData) {
      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/uploads`, formData,
          {headers: {'Content-Type': 'multipart/form-data'}}).then((response) => {
        if (response.status === 201) {
          this.currentStatus = STATUS_SUCCESS;
          this.$refs.addPhoto.closeModal();
        } else {
          this.currentStatus = STATUS_FAILED;
          new Error('error occured during files uploading');
        }
        const {data: data} = response;
        if (data.length) this.uploadedFiles.push(...data);
      }).catch((err) => {
        console.error(err);
      });
    },

    uploadImage: function() {
      this.isDisabled = true;
      const {canvas} = this.$refs.cropper.getResult();

      if (canvas) {
        canvas.toBlob(blob => {
          const formData = new FormData();
          let fileName;

          if (this.imageType !== null) {
            fileName = this.imageType.split('/');
            fileName = fileName[fileName.length - 1];
          }

          formData.append('files', blob, `cover.${fileName}`);

          this.currentStatus = STATUS_SAVING;

          this.save(formData);
        }, this.imageType);
      }
    },

    addImage(event) {
      const input = event.target;

      if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = (e) => {
          this.photoInProgress = e.target.result;
          this.imageType = this.$getMimeType(e.target.result, input.files[0].type);
        };
        reader.readAsDataURL(input.files[0]);
      }

      this.$refs.addPhoto.openModal();
    },
  },
};
</script>
