<template>
  <div>
    <h2 class="title">
      Новая новость
    </h2>

    <section class="profile__section">
      <UploadWithCrop
        :photos.sync="cover"
        stencil-component="rectangle-stencil"
        description="Выбрать обложку"
      />
    </section>

    <section class="profile__section">
      <div class="">
        <div class="input-wrapper">
          <TextInput
            class="invert event-name"
            :class="$v.news.title.$invalid ? 'error' : ''"
            :margin="0"
            type="text"
            placeholder="Заголовок"
            v-model="news.title"
            :isLabel="false"
            :required="true"
            @keydown.native="$v.$touch"
          />
        </div>
        <div class="input-wrapper">
          <TextEditor
            v-model="news.text"
            :class="$v.news.text.$invalid ? 'error' : ''"
            @keydown.native="$v.$touch"
          />
        </div>
      </div>
    </section>
    <section class="profile__section">
      <Upload :photos.sync="images" :upload-field-name="'files[]'" />
    </section>
    <section class="profile__section">
      <div class="row mb-24">
        <div class="col-50">
          <Checkbox
            :id="'allowComments'"
            :checked="news.comments_enabled"
            :label="'Открыть комментарии'"
            @change="news.comments_enabled = $event"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <Checkbox
            :id="'is_published'"
            :checked="news.is_published"
            :label="'Публиковать на сайте'"
            @change="news.is_published = $event"
          />
        </div>
      </div>
    </section>
    <section class="profile__section">
      <Button
        @click.native="addNews"
        :is-success="isSaved"
        :is-spinner="isLoading"
        :disabled="$v.$invalid"
        class="btn--blue"
      >
        {{ isSaved ? 'Сохранено' : 'Сохранить' }}
      </Button>
    </section>
    <formAutoSaver
      :observable-fields.sync="news"
      :save-func="addNews"
      :is-saved="isSaved"
    />
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'ProfileOrgAddJournalNewsView',
  computed: {
    orgInfo: function() {
      return this.$organization;
    },
  },
  components: {
    formAutoSaver,
  },
  data: function() {
    return {
      news: {
        title: '',
        text: '',
        comments_enabled: true,
        is_published: true,
        type: 1,
      },
      images: [],
      cover: [],
      isLoading: false,
      isDisabled: false,
      isSaved: false,
      signal: null,
      loadTrigger: 0,
      dWatcher: null,
    };
  },
  validations: {
    news: {
      title: { required },
      text: { required },
    },
  },
  created() {
    if (this.orgInfo.length !== 0) {
      this.orgId = this.orgInfo[0].id;
    }
  },
  watch: {
    orgInfo(val) {
      if (val.length !== 0) {
        this.orgId = val[0].id;
      }
    },
    // news: {
    //   handler: function() {
    //     if(this.loadTrigger) {
    //       this.$v.$touch();
    //       this.loadTrigger++;
    //     } else this.loadTrigger++
    //   },
    //   deep: true,
    //   immediate: false,
    // },
    // $v: {
    //   handler: function(val) {
    //     if (val.$invalid && val.$dirty) {
    //       this.isSaved = false;
    //     }
    //   },
    //   deep: true,
    // },
  },
  
  methods: {
    clearData() {
      this.news.title = '';
      this.news.text = '';
    },
    addNews() {
      this.dWatcher && this.dWatcher();
      this.isLoading = true;
      this.$http
        .post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgId}/journal/`,
          {
            ...this.news,
            images: this.images,
            cover: this.cover,
          }
        )
        .then((response) => {
          if (response.status === 201) {
            // console.log(response);
            this.isLoading = false;
            this.isSaved = true;
            this.isDisabled = true;
            // this.clearData();
            // this.$v.$reset();
            // console.log('dirty', this.$v.$dirty);
            // this.dWatcher = this.$watch(
            //   'news',
            //   function() {
            //     this.isSaved = false;
            //   },
            //   { deep: true }
            // );
            this.$router.push({ name: 'ProfileOrgEditJournalNewsView', params: {id: response.data.id, isSaved: true} });
          } else throw Error('error occured while journal news adding');
        })
        .catch((err) => {
          this.isLoading = false;
          console.log(err);
        });
    },
  },
};
</script>
<style scoped lang="scss">
.mb-24 {
  margin-bottom: 24px;
}

.profile__section {
  margin-bottom: 34px;

  .input-wrapper {
    margin-bottom: 24px;
    position: relative;

    &.reading-duration {
      width: 293px;

      .reading-duration-icon {
        width: 16px;
        height: 16px;
        top: 12px;
        right: 12px;
        line-height: 1;
        position: absolute;
      }
    }
  }

  .checkbox-container:not(:last-child) {
    margin-bottom: 28px;
  }
}

.title {
  margin-bottom: 32px;
}

.grid {
  display: grid;
  grid-gap: 24px 32px;
}

.grid-col-2 {
  grid-template-columns: repeat(2, 1fr);
}

/deep/ .checkbox-container__text {
  padding-left: 12px;
}

/deep/ .editr {
  border: none;
  border-radius: 8px;
  overflow: hidden;

  &--content {
    background-color: var(--main-color-trans-light);
  }
}
</style>
