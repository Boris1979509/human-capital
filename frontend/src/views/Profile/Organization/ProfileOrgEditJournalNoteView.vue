<template>
  <div>
    <h2 class="title">
      Редактировать статью
    </h2>

    <section class="profile__section">
      <UploadWithCrop :photos.sync="cover" :preview="previewCover" stencil-component="rectangle-stencil"
                      description="Выбрать обложку"/>
    </section>

    <section class="profile__section">
      <div class="">
        <div class="input-wrapper">
          <TextInput
              class="invert event-name"
              :class="$v.article.title.$invalid ? 'error' : ''"
              :margin="0"
              type="text"
              placeholder="Заголовок"
              v-model="article.title"
              :isLabel="false"
              :required="true"
          />
        </div>
        <div class="input-wrapper">
          <TextEditor v-model="article.text" :class="$v.article.text.$invalid ? 'error' : ''"/>
        </div>
      </div>
    </section>
    <section class="profile__section">
      <Upload
          :photos.sync="images"
          :upload-field-name="'files[]'"
          :preview="preview"
      />
    </section>
    <section class="profile__section">
      <div class="row mb-24">
        <div class="col-50">
          <Checkbox
              :id="'allowComments'"
              :checked="article.comments_enabled"
              :label="'Открыть комментарии'"
              @change="article.comments_enabled = $event"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <Checkbox
              :id="'is_published'"
              :checked="article.is_published"
              :label="'Публиковать на сайте'"
              @change="article.is_published = $event"
          />
        </div>
      </div>
    </section>
    <section class="profile__section">
      <Button
          @click.native="editArticle"
          :is-success="isSaved"
          :disabled="$v.$invalid"
          class="btn--blue mb-16"
      >
        {{ isSaved ? 'Сохранено' : 'Сохранить' }}
      </Button>
      <Button
          @click.native="$refs.modalName.openModal()"
          :disabled="isDisabled"
          class="btn--red"
      >
        Удалить
      </Button>
    </section>
    <modal ref="modalName">
      <template v-slot:header></template>

      <template v-slot:body>
        <p class="modal_body_text">Удалить статью?</p>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <div class="btn btn--red" @click="deleteEvent">
            Удалить
          </div>
          <div class="btn btn--blue" @click="$refs.modalName.closeModal()">
            Отмена
          </div>
        </div>
      </template>
    </modal>
    <formAutoSaver
      :observable-fields.sync="article"
      :save-func="editArticle"
      :is-saved="isSaved"
      :update-via-signal="true"
      :signal="signal"
    />
  </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'ProfileOrgEditJournalNoteView',
  components: {
    formAutoSaver,
  },
  computed: {
    orgInfo: function() {
      return this.$organization;
    },
  },
  data: function() {
    return {
      article: {
        title: '',
        text: '',
        comments_enabled: false,
        is_published: false,
        type: 2,
      },
      images: [],
      cover: [],
      preview: [],
      previewCover: [],
      isDisabled: false,
      isSaved: false,
      signal: false,
    };
  },
  watch: {
    article: {
      handler: function() {
        this.isSaved && (this.isSaved = false);
      },
      deep: true,
    },
  },
  validations: {
    article: {
      title: {required},
      text: {required},
    },
  },
  created() {
    if (this.orgInfo.length !== 0) {
      this.orgId = this.orgInfo[0].id;
    }
    this.$http.get(
        `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
    ).then((response) => {
      if (response.status === 200) {
        console.log(response);
        const {
          data: {data: data},
        } = response;
        this.article = {
          ...this.article,
          ...data,
        };
        this.previewCover = [...data.cover];
        this.preview = [...data.images];
        this.signal = true;
          if (this.$route.params.isSaved) {
            this.$nextTick(() => this.isSaved = true) 
          }
      } else throw Error('error occured while journal event getting');
    }).catch((err) => {
      console.log(err);
    });
  },
  methods: {
    editArticle() {
      if (this.article.cover) delete this.article.cover;
      this.isDisabled = true;
      this.$http.put(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
          {
            ...this.article,
            images: this.images,
            cover: this.cover,
            type: 2,
          },
      ).then((response) => {
        if (response.status === 200) {
          console.log(response);
          this.isSaved = true;
          this.isDisabled = false;
        } else throw Error('error occured while journal event editing');
      }).catch((err) => {
        console.log(err);
      });
    },
    deleteEvent() {
      this.$http.delete(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
      ).then((response) => {
        if (response.status === 204) {
          console.log(response);
          this.isSaved = true;
          this.$router.push({
            name: 'ProfileOrgJournalListView',
          });
        } else throw Error('error occured while journal note deleting');
      }).catch((err) => {
        console.log(err);
      });
    },
  },
};
</script>
<style scoped lang="scss">
// commom util styles
.mb-24 {
  margin-bottom: 24px;
}

.mb-16 {
  margin-bottom: 24px;
}

// modal
.modal_body_text {
  font-family: Golos;
  font-size: 32px;
  font-style: normal;
  font-weight: 700;
  line-height: 36px;
}

.btn-wrapper {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-gap: 50px;
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
