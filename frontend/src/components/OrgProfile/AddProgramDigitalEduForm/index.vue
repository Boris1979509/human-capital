<template>
  <div class="profile__container">
    <h4 class="title">
      Новая программа
    </h4>

    <section class="profile__section">
      <h5 class="title profile__subtitle">
        Общая информация
      </h5>

      <div class="row">
        <div class="col-100">
          <TextInput class="invert"
                     :class="$v.fields.name.$invalid ? 'error' : ''"
                     type="text"
                     placeholder="Наименование образовательной программы"
                     v-model="fields.name"
                     :isLabel="false"
                     :required="true"/>
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <Checkbox :id="'isVisible'"
                    label="Показывать на сайте"
                    :margin="24"
                    :checked="fields.is_published"
                    @change="fields.is_published = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <TextInput class="invert"
                     :class="$v.fields.direction_of_study.$invalid ? 'error' : ''"
                     type="text"
                     placeholder="Направление обучения"
                     v-model="fields.direction_of_study"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-50">
          <Select :array="inst_program"
                  :class="$v.fields.type_id.$invalid ? 'error' : ''"
                  placeholder="Тип образовательной программы"
                  :pre-selected="fields.type_id"
                  @select="fields.type_id = $event"/>
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <TextEditor
              style="margin-bottom: 20px;"
              v-model="fields.description"
              :class="$v.fields.description.$invalid ? 'error' : ''"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="Количество свободных мест"
                     v-model="fields.free_places"
                     :isLabel="false"
                     :required="true"/>
        </div>
      </div>
    </section>

    <section class="profile__section">
      <h5 class="title profile__subtitle">
        Сформированные компетенции и навыки
      </h5>

      <div class="row">
        <div class="col-100">
          <TagsCloud :tags="fields.competitions"
                     @updateTags="fields.competitions.push($event)"
                     :class="$v.fields.competitions.$invalid ? 'error' : ''"
                     placeholder="Компетенции и навыки, например, управление финансами"
                     :required="true"
                     :margin="0"/>
        </div>
      </div>
    </section>

    <section class="profile__section">
      <h5 class="title profile__subtitle">
        Условия поступления
      </h5>

      <div class="row">
        <div class="col-100">
          <Checkbox id="ege"
                    label="Есть вступительные испытания"
                    :checked="fields.is_admission_exam"
                    :margin="16"
                    @change="fields.is_admission_exam = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <TextEditor
              style="margin-bottom: 20px;"
              v-model="fields.admission_olympiad_conditions"
          />
        </div>
      </div>
    </section>

    <section class="profile__section">
      <div v-for="(item, key) in $v.fields.learning_options.$each.$iter" :key="key" class="profile__group">
        <div class="profile__section-header">
          <h5 class="title">
            Вариант обучения {{ parseInt(key) + 1 }}
          </h5>

          <Button @click.native="removeSingle(key)" class="link-svg" v-if="fields.learning_options.length > 1">
            <Icon xlink="delete"
                  viewport="0 0 16 16"/>
          </Button>
        </div>

        <section class="profile__section">
          <div class="row">
            <div class="col-50">
              <Select :array="inst_auditory"
                      :class="item.auditory.$invalid ? 'error' : ''"
                      placeholder="Целевая аудитория"
                      :pre-selected="item.auditory.$model"
                      @select="item.auditory.$model = $event"/>
            </div>

            <div class="col-50">
              <Select :array="[{name: '3-6 лет', id: 0}, {name: '6-9 лет', id: 1}]"
                      placeholder="Возраст"
                      :pre-selected="item.age.$model"
                      @select="item.age.$model = $event"/>
            </div>
          </div>

          <div class="row">
            <div class="col-50">
              <Select :array="[{name: 'В год', id: 0}, {name: 'В месяц', id: 1}]"
                      placeholder="Период оплаты"
                      :pre-selected="item.edu_pay_period.$model"
                      @select="item.edu_pay_period.$model = $event"/>
            </div>

            <div class="col-50">
              <TextInput class="invert"
                         type="text"
                         placeholder="Стоимость, ₽"
                         inputMask="###############"
                         v-model="item.cost.$model"
                         :isLabel="false"
                         :required="true"/>
            </div>
          </div>

          <div class="row">
            <div class="col-50">
              <TextInput class="invert"
                         :class="item.how_long.$invalid ? 'error' : ''"
                         type="text"
                         placeholder="Срок обучения"
                         v-model="item.how_long.$model"
                         :isLabel="false"
                         :required="true"/>
            </div>

            <div class="col-50">
              <TextInput class="invert"
                         :class="item.start_date.$invalid ? 'error' : ''"
                         type="text"
                         placeholder="Дата начала обучения"
                         v-model="item.start_date.$model"
                         :isLabel="false"
                         :required="true"/>
            </div>
          </div>

          <div class="row">
            <div class="col-50">
              <TextInput class="invert"
                         :class="item.duration.$invalid ? 'error' : ''"
                         type="text"
                         placeholder="Длительность обучения"
                         v-model="item.duration.$model"
                         :isLabel="false"
                         :required="true"/>
            </div>
          </div>
        </section>
      </div>

      <Button @click.native="addSingle" class="btn--light">
        <Icon xlink="plus"
              viewport="0 0 16 16"/>
        Добавить вариант обучения
      </Button>
    </section>

    <section class="profile__section">
      <h5 class="title profile__subtitle">
        Профессии после выпуска
      </h5>

      <div class="row">
        <div class="col-100">
          <TagsCloud :tags="fields.result_professions"
                     @updateTags="fields.result_professions.push($event)"
                     :class="$v.fields.result_professions.$invalid ? 'error' : ''"
                     placeholder="Введите ключевое слово, например, инженер"
                     :required="true"
                     :margin="40"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <Checkbox id="reviews_available"
                    label="Можно оставлять отзывы о программе"
                    :margin="24"
                    :checked="fields.questions_enabled"
                    @change="fields.questions_enabled = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <Checkbox id="questions_available"
                    label="Можно задавать вопросы ВУЗу"
                    :checked="fields.reviews_enabled"
                    @change="fields.reviews_enabled = $event"
          />
        </div>
      </div>
    </section>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <Button @click.native="updateOrCreate" :is-success="isSaved" :is-spinner="isLoading" :disabled="$v.$invalid"
                  class="btn--blue">
            {{ isSaved ? 'Сохранено' : 'Сохранить' }}
          </Button>
        </div>

        <div class="col-100" v-if="isDeleteAvailable">
          <Button @click.native="$refs.deleteProgram.openModal()"
                  class="btn--delete"
                  style="margin-top: 24px;"
          >
            <Icon xlink="delete" viewport="0 0 16 16"/>
            Удалить программу
          </Button>
        </div>
      </div>
    </section>

    <modal ref="deleteProgram" :is-default-close="false">
      <template v-slot:header>
        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M3 28C3 14.1929 14.1929 3 28 3C41.8071 3 53 14.1929 53 28C53 33.6309 51.1402 38.823 48.0008 43.0016C47.5032 43.6639 47.6367 44.6042 48.299 45.1018C48.9613 45.5994 49.9016 45.4659 50.3992 44.8036C53.9156 40.1233 56 34.3029 56 28C56 12.536 43.464 0 28 0C12.536 0 0 12.536 0 28C0 43.464 12.536 56 28 56C34.3017 56 40.121 53.9165 44.8009 50.4013C45.4633 49.9038 45.5969 48.9635 45.0993 48.3011C44.6018 47.6387 43.6615 47.5051 42.9991 48.0026C38.821 51.1409 33.6298 53 28 53C14.1929 53 3 41.8071 3 28Z"
              fill="#E14761"/>
          <path
              d="M28 37C27.1716 37 26.5 36.3284 26.5 35.5V12C26.5 11.1716 27.1716 10.5 28 10.5C28.8284 10.5 29.5 11.1716 29.5 12V35.5C29.5 36.3284 28.8284 37 28 37Z"
              fill="#E14761"/>
          <path
              d="M26 42C26 40.8954 26.8954 40 28 40C29.1046 40 30 40.8954 30 42C30 43.1046 29.1046 44 28 44C26.8954 44 26 43.1046 26 42Z"
              fill="#E14761"/>
        </svg>
      </template>

      <template v-slot:body>
        <h2 class="modal__title title">
          Удалить программу?
        </h2>

        <p class="modal__description">
          Вы потеряете все введенные данные
        </p>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <Button class="btn btn--light" @click.native="$refs.deleteProgram.closeModal()">
            Продолжить редактирование
          </Button>

          <Button class="btn btn--red" @click.native="deleteProgram">
            Удалить
          </Button>
        </div>
      </template>
    </modal>
    <formAutoSaver
      :observable-fields.sync="fields"
      :save-func="sendData"
      :is-saved="isSaved"
    />
  </div>
</template>

<script>
import Vue from 'vue';
import {required} from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'AddProgramDigitalEduForm',

  components: {
    formAutoSaver,
  },

  computed: {
    orgInfo: function() {
      return this.$organization;
    },
    inst_program: function() {
      return this.$dictionaries.inst_program;
    },
    inst_item: function() {
      return this.$dictionaries.inst_item;
    },
    inst_auditory: function() {
      return this.$dictionaries.inst_auditory;
    },
    inst_form: function() {
      return this.$dictionaries.inst_form;
    },
    program: function() {
      return this.$program;
    },
  },

  created() {
    if (this.$route.path.includes('edit') &&
        this.$route.params.curriculum?.length !== 0 &&
        this.$route.params.institution?.length !== 0) {
      if (this.program?.data.length !== 0) {
        this.fields = {...this.program.data};
        this.isDeleteAvailable = true;
      }
    } else {
      this.fields = {...this.default};
      this.isDeleteAvailable = false;
    }
    if (this.orgInfo.length !== 0) {
      this.fields.org_id = this.orgInfo[0].id;
    }
  },

  data: function() {
    return {
      learning_option: {
        auditory: null,
        age: null,
        edu_pay_period: null,
        cost: '',
        how_long: '',
        start_date: '',
        duration: '',
      },

      fields: {
        org_id: '',

        id: '',

        name: '',
        is_published: false,
        direction_of_study: '',
        type_id: null,
        description: '',

        free_places: '',

        competitions: [],

        is_admission_exam: false,
        admission_olympiad_conditions: '',

        learning_options: [
          {
            auditory: null,
            age: null,
            edu_pay_period: null,
            cost: '',
            how_long: '',
            start_date: '',
            duration: '',
          },
        ],

        result_professions: [],

        questions_enabled: false,
        reviews_enabled: false,
      },

      default: {
        org_id: '',

        id: '',

        name: '',
        is_published: false,
        direction_of_study: '',
        type_id: null,
        description: '',

        free_places: '',

        competitions: [],

        is_admission_exam: false,
        admission_olympiad_conditions: '',

        learning_options: [
          {
            auditory: null,
            age: null,
            edu_pay_period: null,
            cost: '',
            how_long: '',
            start_date: '',
            duration: '',
          },
        ],

        result_professions: [],

        questions_enabled: false,
        reviews_enabled: false,
      },

      submitDisabled: false,
      isSaved: false,
      isLoading: false,
      isDeleteAvailable: false,
    };
  },

  validations: {
    fields: {
      name: {required},
      direction_of_study: {required},
      description: {required},
      type_id: {required},
      competitions: {required},
      duration: {},
      learning_options: {
        $each: {
          auditory: {required},
          how_long: {required},
          start_date: {required},
          duration: {required},
          passing_score: {},
          available_places: {},
          cost: {},
          age: {},
          edu_pay_period: {},
        },
      },
      result_professions: {required},
    },
  },

  methods: {
    addSingle: function() {
      this.fields.learning_options.push(Vue.util.extend({}, this.learning_option));
    },

    removeSingle: function(index) {
      Vue.delete(this.fields.learning_options, index);
    },

    addSubject: function() {
      this.fields.admission_exams.push(Vue.util.extend({}, this.admission_exam));
    },

    removeSubject: function(index) {
      Vue.delete(this.fields.admission_exams, index);
    },

    updateOrCreate: function() {
      if (this.fields.id.length !== 0) {
        this.sendData(this.fields.id);
      } else {
        this.sendData(null);
      }
    },

    sendData: function(id) {
      this.submitDisabled = true;
      this.isSaved = false;
      this.isLoading = true;

      if (this.fields.org_id.length !== 0) {
        if (id) {
          this.$http.put(
              `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.fields.org_id}/curricula/${id}`,
              {...this.fields}).then((response) => {
            if (response.status === 200) this.isSaved = true;
            this.isLoading = false;
            this.$router.push({name: 'ProfileOrgProgramsView'});
          }).finally(() => {
            this.submitDisabled = false;
            this.isLoading = false;
          });
        } else {
          this.$http.post(
              `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.fields.org_id}/curricula`,
              {...this.fields}).then((response) => {
            if (response.status === 201) this.isSaved = true;
            this.isLoading = false;
            this.$router.push({name: 'ProfileOrgProgramsView'});
          }).finally(() => {
            this.submitDisabled = false;
            this.isLoading = false;
          });
        }
      }
    },
    deleteProgram: function() {
      this.$http.delete(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.fields.org_id}/curricula/${this.fields.id}`).
          then(() => {
            this.isSaved = true;
            this.$router.push({name: 'ProfileOrgProgramsView'});
          });
    },
  },
};
</script>

<style lang="scss">
.btn-wrapper {

  .btn:first-child {
    margin-bottom: 16px;
  }
}

.modal {

  &__title {
    margin-bottom: 16px;
  }

  &__description {
    font-weight: normal;
    font-size: 16px;
    line-height: 20px;
    color: #000;
  }
}
</style>
