<template>
  <div>
    <h2 class="title">
      Новая запись
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
          <TextEditor
            v-model="article.text"
            :class="$v.article.text.$invalid ? 'error' : ''"
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
        @click.native="addArticle"
        :is-success="isSaved"
        :disabled="$v.$invalid"
        class="btn--blue"
      >
        {{ isSaved ? 'Сохранено' : 'Сохранить' }}
      </Button>
    </section>
    <formAutoSaver
      :observable-fields.sync="article"
      :save-func="addArticle"
      :is-saved="isSaved"
    />
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'ProfileOrgAddJournalNoteView',
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
        comments_enabled: true,
        is_published: true,
        type: 2,
      },
      images: [],
      cover: [],
      isDisabled: false,
      isSaved: false,
    };
  },
  validations: {
    article: {
      title: { required },
      text: { required },
    },
  },
  created() {
    if (this.orgInfo.length !== 0) {
      this.orgId = this.orgInfo[0].id;
    }
  },
  methods: {
    addArticle() {
      this.isDisabled = true;
      this.$http
        .post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgId}/journal/`,
          {
            ...this.article,
            images: this.images,
            cover: this.cover,
          }
        )
        .then((response) => {
          if (response.status === 201) {
            this.isSaved = true;

            this.$router.push({
              name: 'ProfileOrgEditJournalNoteView',
              params: { id: response.data.id, isSaved: true },
            });
          } else throw Error('error occured while journal article adding');
        })
        .catch((err) => {
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
