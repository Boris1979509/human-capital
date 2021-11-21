<template>
  <div class="upload-container" :style="`margin-bottom: ${margin}px`">
    <form enctype="multipart/form-data" novalidate>
      <div class="dropbox">
        <input
          type="file"
          :name="uploadFieldName"
          @change="
            filesChange($event.target.name, $event.target.files);
            fileCount = $event.target.files.length;
          "
          accept="image/jpg, image/png, image/gif"
          class="dropbox__input-file"
          :disabled="isSaving"
          multiple
        />

        <div class="dropbox__main-text">
          <span>
            <Icon xlink="attach" viewport="0 0 16 16" />
            Загрузить фото
          </span>

          <span v-if="isSuccess">
            Успешно добавлены {{ uploadedFiles.length }} файл(ов).
          </span>

          <span v-else-if="isFailed">
            Ошибка загрузки... {{ uploadError }}
          </span>

          <span v-else>
            Поддерживаемые форматы: jpg, png, gif Файл не более 10 МБайт
          </span>
        </div>

        <!--          <p v-if="isSaving">-->
        <!--            Загружаем {{ fileCount }} файл(ов)...-->
        <!--          </p>-->
      </div>
    </form>

    <div class="upload" v-if="uploadedFiles.length > 0">
      <div
        class="upload__item-container"
        v-for="(item, key) in uploadedFiles"
        :key="key"
      >
        <div class="upload__item">
          <div class="upload__item-icon">
            <img :src="item.url" :alt="item.title" class="upload__image" />
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
              <Icon xlink="delete" viewport="0 0 16 16" />
            </Button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
const STATUS_INITIAL = 0,
  STATUS_SAVING = 1,
  STATUS_SUCCESS = 2,
  STATUS_FAILED = 3;

export default {
  name: 'Upload',

  props: {
    margin: {
      type: Number,
      default: () => 20,
    },
    preview: {
      type: Array,
      default: () => [],
    },
    uploadFieldName: {
      type: String,
      default: () => 'files[]',
    },
    singleFile: {
      type: Boolean,
      default: () => false,
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
      fileCount: null,
      uploadedFiles: [],
      uploadedFilesIds: [],
      uploadError: null,
      currentStatus: null,
    };
  },

  watch: {
    uploadedFiles(val) {
      this.uploadedFilesIds = val.map((item) => item.id);
      this.singleFile
        ? this.$emit('update:photos', this.uploadedFilesIds[0])
        : this.$emit('update:photos', [...this.uploadedFilesIds]);
    },
    preview: {
      handler: function(val) {
        this.uploadedFiles = [...val];
      },
      immediate: true,
    },
  },

  methods: {
    reset() {
      this.currentStatus = STATUS_INITIAL;
      this.uploadError = null;
      this.fileCount = null;
    },

    wait: function wait(ms) {
      return (x) => {
        return new Promise((resolve) => setTimeout(() => resolve(x), ms));
      };
    },

    save: function(formData) {
      this.currentStatus = STATUS_SAVING;

      this.$http
        .post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/uploads`,
          formData
        )
        .then((response) => {
          if (response.status === 201) {
            this.currentStatus = STATUS_SUCCESS;
          } else {
            this.currentStatus = STATUS_FAILED;
            new Error('error occured during files uploading');
          }
          const { data: data } = response;

          if (data.length) this.uploadedFiles.push(...data);
        })
        .catch((err) => {
          console.error(err);
        });
    },

    filesChange: function(fieldName, fileList) {
      const formData = new FormData();
      if (!fileList.length) return;

      Array.from(Array(fileList.length).keys()).map((x) => {
        formData.append(fieldName, fileList[x], fileList[x].name);
      });
      this.save(formData);
    },

    removeFile: function(id) {
      this.uploadedFiles = this.uploadedFiles.filter((item) => item.id !== id);
    },
  },
};
</script>
