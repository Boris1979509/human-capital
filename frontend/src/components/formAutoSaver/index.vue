<template>
  <div class="form-auto-saver">
    <modal ref="modalName">
      <template v-slot:header>
        <svg
          width="56"
          height="56"
          viewBox="0 0 56 56"
          fill="none"
          xmlns="http://www.w3.org/2000/svg"
        >
          <path
            d="M3 28C3 14.1929 14.1929 3 28 3C41.8071 3 53 14.1929 53 28C53 33.6309 51.1402 38.823 48.0008 43.0016C47.5032 43.6639 47.6367 44.6042 48.299 45.1018C48.9613 45.5994 49.9016 45.4659 50.3992 44.8036C53.9156 40.1233 56 34.3029 56 28C56 12.536 43.464 0 28 0C12.536 0 0 12.536 0 28C0 43.464 12.536 56 28 56C34.3017 56 40.121 53.9165 44.8009 50.4013C45.4633 49.9038 45.5969 48.9635 45.0993 48.3011C44.6018 47.6387 43.6615 47.5051 42.9991 48.0026C38.821 51.1409 33.6298 53 28 53C14.1929 53 3 41.8071 3 28Z"
            fill="#E14761"
          />
          <path
            d="M28 37C27.1716 37 26.5 36.3284 26.5 35.5V12C26.5 11.1716 27.1716 10.5 28 10.5C28.8284 10.5 29.5 11.1716 29.5 12V35.5C29.5 36.3284 28.8284 37 28 37Z"
            fill="#E14761"
          />
          <path
            d="M26 42C26 40.8954 26.8954 40 28 40C29.1046 40 30 40.8954 30 42C30 43.1046 29.1046 44 28 44C26.8954 44 26 43.1046 26 42Z"
            fill="#E14761"
          />
        </svg>
      </template>

      <template v-slot:body>
        <p class="modal__body__text">Сохранить изменения?</p>
        <p class="modal__body__description">
          Вы потеряете введённые данные,если покинете страницу без сохранения
        </p>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <div
            class="btn btn--blue"
            @click="
              $refs.modalName.closeModal();
              saveFunc();
              exitWithoutSave();
              nextRoute();
            "
          >
            Сохранить изменения
          </div>
          <div
            class="btn btn--red"
            @click="
              exitWithoutSave();
              nextRoute();
            "
          >
            Уйти со страницы
          </div>
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
export default {
  name: 'formAutoSaver',
  props: {
    observableFields: Object,
    saveFunc: {
      type: Function,
      required: true,
    },
    isSaved: Boolean,
    signal: {
      type: Boolean,
      default: false,
    },
    updateViaSignal: {
      type: Boolean,
      default: false,
    },
  },
  data() {
    return {
      parentComponentPath: '',
      parentComponentName: '',
      nextRoute: null,
      observableFieldsStringified: '',
      observableFieldsChanges: 0,
      observableFieldsChanged: false,
    };
  },

  created() {
    if (!window.localStorage) this.$destroy();
    this.parentComponentPath = this.$parent.$route.path;
    this.parentComponentName = this.$parent.$route.name;
    if (!window.localStorage.getItem('REVISION: 25.05.2021')) {
      window.localStorage.removeItem(this.parentComponentPath);
      window.localStorage.setItem('REVISION: 25.05.2021', '[]');
    }

    window.addEventListener('beforeunload', this.emergencySave);

    if (!this.updateViaSignal) this.loadData();
  },
  mounted() {
    if(Object.keys(this.observableFields)) this.observableFieldsStringified = JSON.stringify(this.observableFields)
    this.$parent.$router.beforeResolve((to, from, next) => {
      if (!this.isSaved && this.observableFieldsChanged) {
        this.nextRoute = next;
        this.$refs.modalName.openModal();
      } else next(true);
    });
  },
  beforeDestroy() {
    window.removeEventListener('beforeunload', this.emergencySave);
    this.$parent.$router.resolveHooks.pop();
    this.exitWithoutSave();
    delete this.observableFields;
  },
  watch: {
    signal(newValue) {
      if (newValue) this.loadData();
    },
    observableFields: {
      handler: function(newVal) {
        if(JSON.stringify(newVal) !== this.observableFieldsStringified) this.observableFieldsChanged = true;
      },
      deep: true,
    },
  },
  methods: {
    isJson(item) {
      item = typeof item !== 'string' ? JSON.stringify(item) : item;

      try {
        item = JSON.parse(item);
      } catch (e) {
        return false;
      }

      if (typeof item === 'object' && item !== null) {
        return true;
      }

      return false;
    },
    loadData() {
      const pageData = this.getPageData(this.parentComponentPath);
      const parentComponentData =
        (pageData !== null &&
          typeof pageData === 'object' &&
          this.getParentComponentData(this.parentComponentName, pageData)) ||
        {};
      Object.keys(parentComponentData) &&
        Object.keys(parentComponentData).length &&
        this.$emit('update:observableFields', parentComponentData);
    },
    exitWithoutSave() {
      localStorage.removeItem(this.parentComponentPath);
    },
    emergencySave() {
      let pageData = this.getPageData(this.parentComponentPath);
      if (pageData) {
        pageData[this.parentComponentName] = this.observableFields;
        this.setPageData(this.parentComponentPath, pageData);
      } else {
        this.setPageData(this.parentComponentPath, {
          [this.parentComponentName]: this.observableFields,
        });
      }
    },
    setPageData(name, data) {
      window.localStorage.setItem(name, JSON.stringify(data));
    },
    getPageData(pagePath) {
      const data = window.localStorage.getItem(pagePath);
      return data && this.isJson(data) && JSON.parse(data);
    },
    getParentComponentData(name, pageData) {
      if (!pageData) return;
      let data = pageData;
      if (typeof data === 'string') data = JSON.parse(pageData);
      return data[name];
    },
  },
};
</script>

<style lang="scss" scoped>
::v-deep {
  .modal {
    &__close {
      display: none;
    }
    &__header {
      margin-bottom: 32px;
    }
    &__body {
      &__text {
        font-size: 32px;
        font-style: normal;
        font-weight: 800;
        color: var(--main-color-dark);
        line-height: 36px;
        margin-bottom: 16px;
      }
      &__description {
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        color: var(--main-color-dark);
        line-height: 20px;
      }
    }
    &__footer {
      .btn--blue {
        margin-bottom: 16px;
      }
    }
  }
}
</style>
