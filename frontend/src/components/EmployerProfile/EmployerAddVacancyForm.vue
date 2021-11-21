<template>
  <div class="profile__container" v-if="!isLoading">
    <h4 class="title">
      {{ $route.path.includes('edit') ? 'Редактирование вакансии' : 'Новая вакансия' }}
    </h4>
    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <TextInput class="invert"
                     :class="$v.fields.name.$invalid ? 'error' : ''"
                     type="text"
                     placeholder="Название вакансии"
                     v-model="fields.name"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-50">
          <Select :array="professions"
                  :class="$v.fields.profession_id.$invalid ? 'error' : ''"
                  placeholder="Профессиональная область"
                  :pre-selected="fields.profession_id"
                  @select="fields.profession_id = $event"
                  :margin="0"/>
        </div>
      </div>
    </section>

    <section class="profile__section">
      <h6 class="title" style="margin-bottom: 16px;">
        Зарплата в месяц
      </h6>

      <div class="row">
        <div class="col-50">
          <div class="row">
            <div class="col-50">
              <TextInput class="invert"
                         :class="$v.fields.salary_min.$invalid ? 'error' : ''"
                         type="text"
                         placeholder="От, руб."
                         v-model="fields.salary_min"
                         :isLabel="false"
                         :required="true"/>
            </div>

            <div class="col-50">
              <TextInput class="invert"
                         :class="$v.fields.salary_max.$invalid ? 'error' : ''"
                         type="text"
                         placeholder="До, руб."
                         v-model="fields.salary_max"
                         :isLabel="false"
                         :required="true"/>
            </div>
          </div>
        </div>

        <div class="col-50">
          <Checkbox id="salary_negotiable"
                    label="Указать «По договорённости»"
                    :margin="24"
                    :checked="fields.salary_negotiable"
                    @change="fields.salary_negotiable = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <Select :array="inst_program"
                  :class="$v.fields.experience_level.$invalid ? 'error' : ''"
                  placeholder="Требуемый опыт"
                  :pre-selected="fields.experience_level"
                  @select="fields.experience_level = $event"/>
        </div>

        <div class="col-50">
          <Select :array="inst_program"
                  :class="$v.fields.employment_type.$invalid ? 'error' : ''"
                  placeholder="Занятость"
                  :pre-selected="fields.employment_type"
                  @select="fields.employment_type = $event"/>
        </div>

        <div class="col-50">
          <Select :array="inst_program"
                  :class="$v.fields.schedule.$invalid ? 'error' : ''"
                  placeholder="График работы"
                  :pre-selected="fields.schedule"
                  @select="fields.schedule = $event"/>
        </div>

        <div class="col-50">
          <Checkbox id="is_internship"
                    label="Стажировка"
                    :margin="24"
                    :checked="fields.is_internship"
                    @change="fields.is_internship = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <TagsCloud :tags="fields.competitions"
                     @updateTags="fields.competitions.push($event)"
                     placeholder="Ключевые компетенции"
                     :class="$v.fields.competitions.$invalid ? 'error' : ''"
                     :required="true"
                     :margin="0"/>
        </div>

        <div class="col-100">
          <TagsCloud :tags="fields.skills"
                     @updateTags="fields.skills.push($event)"
                     placeholder="Ключевые навыки"
                     :class="$v.fields.skills.$invalid ? 'error' : ''"
                     :required="true"
                     :margin="0"/>
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <TextInput :textarea="true"
                     class="invert"
                     placeholder="Обязанности"
                     v-model="fields.responsibilities"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-100">
          <TextInput :textarea="true"
                     class="invert"
                     placeholder="Требования"
                     v-model="fields.requirements"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-100">
          <TextInput :textarea="true"
                     class="invert"
                     placeholder="Условия"
                     v-model="fields.conditions"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-100">
          <TextInput :textarea="true"
                     class="invert"
                     placeholder="Дополнительная информация"
                     v-model="fields.description"
                     :isLabel="false"
                     :required="true"/>
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <Select :array="cities"
                  placeholder="Город"
                  :pre-selected="fields.city_id"
                  @select="fields.city_id = $event"/>
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <AddressInput
              v-model="fields.working_address"
              :coords.sync="fields.coords"
              :invalid="$v.fields.working_address.$invalid "
          />
        </div>

        <div class="col-50">
          <Checkbox id="is_working_address_visible"
                    label="Показывать место работы"
                    :margin="24"
                    :checked="fields.is_working_address_visible"
                    @change="fields.is_working_address_visible = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <Checkbox id="show_chat"
                    label="Показывать чат"
                    :margin="24"
                    :checked="fields.show_chat"
                    @change="fields.show_chat = $event"
          />
        </div>
      </div>
    </section>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <Button @click.native="createOrUpdate" :is-success="isSaved" :is-spinner="isLoading" :disabled="$v.$invalid"
                  class="btn--blue">
            {{ isSaved ? 'Сохранено' : 'Сохранить' }}
          </Button>
        </div>

        <div class="col-100" v-if="isDeleteAvailable">
          <Button @click.native="$refs.deleteVacancy.openModal()"
                  class="btn--delete"
                  style="margin: 24px auto 0;"
          >
            <Icon xlink="delete" viewport="0 0 16 16"/>
            Удалить вакансию
          </Button>
        </div>
      </div>
    </section>

    <modal ref="deleteVacancy" :is-default-close="false">
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
          Удалить вакансию?
        </h2>

        <p class="modal__description">
          Вы потеряете все введенные данные
        </p>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <Button class="btn btn--light" @click.native="$refs.deleteVacancy.closeModal()">
            Продолжить редактирование
          </Button>

          <Button class="btn btn--red" style="margin-top: 24px;" @click.native="deleteVacancy">
            Удалить
          </Button>
        </div>
      </template>
    </modal>
  </div>
  <Loading v-else/>
</template>

<script>
import {required} from 'vuelidate/lib/validators';
import AddressInput from '@/components/InputComponents/AddressInput';

export default {
  name: 'EmployerAddVacancyForm',
  components: {AddressInput},
  props: {
    id: [String, Number],
  },

  computed: {
    inst_program: function() {
      return this.$dictionaries.inst_program;
    },
    professions: function() {
      return this.$professions;
    },
    cities: function() {
      return this.$cities.data;
    },
  },

  created() {
    if (this.$route.path.includes('edit') && this.id) {
      this.getVacancy();
      this.isDeleteAvailable = true;
    } else {
      this.fields = {...this.default};
      this.isDeleteAvailable = false;
    }
  },

  data: function() {
    return {
      fields: {
        id: '',
        name: '',
        profession_id: null,
        salary_min: '',
        salary_max: '',
        salary_negotiable: false,
        experience_level: null,
        employment_type: null,
        schedule: null,
        is_internship: false,
        competitions: [],
        skills: [],
        responsibilities: '',
        requirements: '',
        conditions: '',
        description: '',
        city_id: null,
        working_address: '',
        coords: [],
        is_working_address_visible: false,
        show_chat: false,
      },

      default: {
        id: '',
        name: '',
        profession_id: null,
        salary_min: '',
        salary_max: '',
        salary_negotiable: false,
        experience_level: null,
        employment_type: null,
        schedule: null,
        is_internship: false,
        competitions: [],
        skills: [],
        responsibilities: '',
        requirements: '',
        conditions: '',
        description: '',
        city_id: null,
        working_address: '',
        coords: [],
        is_working_address_visible: false,
        show_chat: false,
      },

      isDeleteAvailable: false,
      isLoading: false,
      submitDisabled: false,
      isSaved: false,
    };
  },

  validations: {
    fields: {
      name: {required},
      profession_id: {required},

      salary_min: {required},
      salary_max: {required},
      experience_level: {required},
      employment_type: {required},
      schedule: {required},
      competitions: {required},
      skills: {required},

      city_id: {required},
      working_address: {required},
    },
  },

  methods: {
    createOrUpdate() {
      if (this.fields.id.length !== 0) {
        this.sendData(this.fields.id);
      } else {
        this.sendData(null);
      }
    },

    getVacancy() {
      this.isLoading = true;
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/vacancies/${this.id}`,
      ).then((response) => {
        this.fields = {...response.data.data};
      }).finally(() => {
        this.isLoading = false;
      });
    },

    sendData: function(id) {
      this.submitDisabled = true;
      this.isSaved = false;
      this.isLoading = true;

      if (id) {
        this.$http.put(
            `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/vacancies/${id}`,
            this.fields).then((response) => {
          if (response.status === 200) this.isSaved = true;
          this.isLoading = false;
          this.$router.push({name: 'ProfileEmployerVacanciesListView'});
        }).catch(() => {
          this.submitDisabled = false;
          this.isLoading = false;
        });
      } else {
        this.$http.post(
            `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/${this.$employer.id}/vacancies`,
            this.fields).then((response) => {
          if (response.status === 201) this.isSaved = true;
          this.isLoading = false;
          this.$router.push({name: 'ProfileEmployerVacanciesListView'});
        }).catch(() => {
          this.submitDisabled = false;
          this.isLoading = false;
        });
      }
    },

    deleteVacancy: function() {
      this.$http.delete(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/vacancies/${this.fields.id}`).then(() => {
        this.$router.push({name: 'ProfileEmployerVacanciesListView'});
      });
    },
  },
};
</script>
