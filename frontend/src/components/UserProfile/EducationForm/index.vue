<template>
  <div class="profile__container">
    <h4 class="title">
      Высшее или среднее специальное образование
    </h4>

    <div v-for="(item, key) in $v.all_edu_data.fields.$each.$iter" :key="key" class="profile__group">
      <div class="profile__section-header">
        <h5 class="title">
          Образование {{ parseInt(key) + 1 }}
        </h5>

        <Button @click.native="removeSingle(key, item.$model.id)" class="link-svg" v-if="all_edu_data.fields.length > 1">
          <Icon xlink="delete"
                viewport="0 0 16 16"/>
        </Button>
      </div>

      <section class="profile__section">
        <div class="row">
          <div class="col-100">
            <Select :array="edu_degree"
                    :class="item.edu_degree_id.$invalid ? 'error' : ''"
                    placeholder="Уровень образования"
                    :pre-selected="item.edu_degree_id.$model"
                    @select="item.edu_degree_id.$model = $event"/>
          </div>

          <div class="col-100">
            <TextInput class="invert"
                       :class="item.university.$invalid ? 'error' : ''"
                       type="text"
                       placeholder="Учебное заведение"
                       v-model="item.university.$model"
                       :isLabel="false"
                       :required="true"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-50">
            <Select :array="edu_status"
                    :class="item.edu_status_id.$invalid ? 'error' : ''"
                    :pre-selected="item.edu_status_id.$model"
                    placeholder="Статус обучения"
                    @select="item.edu_status_id.$model = $event"/>
          </div>

          <div class="col-50">
            <Select
                :array="degreeYear"
                :class="item.year_end.$invalid ? 'error' : ''"
                :pre-selected="(item.year_end.$model < degreeYear[0].name || item.year_end.$model > degreeYear[degreeYear.length - 1].name) ? null : item.year_end.$model"
                :placeholder="item.edu_status_id.$model === 9 ? 'Год окончания' : 'Год выпуска'"
                v-if="!item.edu_status_id.$invalid && item.edu_status_id.$model !== 11"
                @select="item.year_end.$model = $event"/>
          </div>
        </div>

        <div class="row">
          <div class="col-100">
            <TextInput class="invert"
                       :class="item.specialty.$invalid ? 'error' : ''"
                       type="text"
                       placeholder="Специальность"
                       v-model="item.specialty.$model"
                       :isLabel="false"
                       :required="true"
                       :disabled="item.edu_degree_id.$model === 2"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-50">
            <Select :array="edu_quality"
                    :class="item.edu_quality_id.$invalid ? 'error' : ''"
                    :pre-selected="item.edu_quality_id.$model"
                    placeholder="Квалификация"
                    @select="item.edu_quality_id.$model = $event"/>
          </div>

          <div class="col-50">
            <TextInput class="invert"
                       type="text"
                       placeholder="Номер документа об образовании"
                       v-model="item.document_number.$model"
                       :isLabel="false"
                       :required="true"
                       v-if="eduCanHaveDocumentNumber(item.$model)"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-100">
            <Upload :photos.sync="item.files.$model"
                    :preview="item.previewFiles.$model"
                    :upload-field-name="'files[]'"
                    :margin="12"/>
          </div>
        </div>
      </section>
    </div>

    <Button @click.native="addSingle" class="btn--light">
      <Icon xlink="plus"
            viewport="0 0 16 16"/>
      Указать ещё одно место обучения
    </Button>

    <h4 class="title" style="margin-top: 40px;">
      Дополнительное образование
    </h4>

    <div v-for="(item, key) in $v.all_edu_data.additional_education_fields.$each.$iter" :key="'a' + key" class="profile__group">
      <div class="profile__section-header">
        <h5 class="title">
          Дополнительное образование {{ parseInt(key) + 1 }}
        </h5>

        <Button @click.native="removeAdditionalEducation(key)" class="link-svg">
          <Icon xlink="delete"
                viewport="0 0 16 16"/>
        </Button>
      </div>

      <section class="profile__section">
        <div class="row">
          <div class="col-100">
            <TextInput class="invert"
                       :class="item.organization.$invalid ? 'error' : ''"
                       type="text"
                       placeholder="Учебное заведение или проводившая организация"
                       v-model="item.organization.$model"
                       :isLabel="false"
                       :required="true"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-100">
            <TextInput class="invert"
                       :class="item.name.$invalid ? 'error' : ''"
                       type="text"
                       placeholder="Специализация или название"
                       v-model="item.name.$model"
                       :isLabel="false"
                       :required="true"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-50">
            <Select
                :array="degreeYear"
                :class="item.year_end.$invalid ? 'error' : ''"
                :pre-selected="(item.year_end.$model < degreeYear[0].name || item.year_end.$model > degreeYear[degreeYear.length - 1].name) ? null : item.year_end.$model"
                placeholder="Год выпуска"
                @select="item.year_end.$model = $event"/>
          </div>
        </div>
      </section>
    </div>

    <Button @click.native="addAdditionalEducation" class="btn--light">
      <Icon xlink="plus"
            viewport="0 0 16 16"/>
      Указать ещё одно
    </Button>

    <section class="profile__education-submit">
      <div class="row">
        <div class="col-100">
          <Button @click.native="sendData" :disabled="$v.$invalid" :is-success="isSaved" :is-spinner="isLoading"
                  class="btn--blue">
            {{ isSaved ? 'Сохранено' : 'Сохранить' }}
          </Button>
        </div>
      </div>
    </section>
    <formAutoSaver
      :observable-fields.sync="all_edu_data"
      :save-func="sendData"
      :is-saved="isSaved"
    />
  </div>
</template>

<script>
import Vue from 'vue';
import {required, requiredIf} from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'EducationForm',

  components: {
    formAutoSaver,
  },

  computed: {
    personalInfo: function() {
      return this.$personal;
    },
    degreeYear: function() {
      return this.$createYearsRangeFromTo(1950, 2030);
    },
    edu_degree: function() {
      return this.$dictionaries.edu_degree;
    },
    edu_status: function() {
      return this.$dictionaries.edu_status;
    },
    edu_quality: function() {
      return this.$dictionaries.edu_quality;
    },
  },

  created() {
    if (this.personalInfo.education.length !== 0) {
      const data = [...this.personalInfo.education];
      this.all_edu_data.fields = [...this.addPreviews(data)];
    }
    if (this.personalInfo.additional_education.length !== 0) {
      this.all_edu_data.additional_education_fields = this.personalInfo.additional_education;
    }
  },

  data: function() {
    return {
      education: {
        id: '',
        university: '',
        edu_degree_id: '',
        edu_status_id: '',
        edu_quality_id: '',
        year_end: '',
        specialty: '',
        document_number: '',
        files: [],
        previewFiles: [],
      },

      additional_education: {
        organization: '',
        name: '',
        year_end: '',
      },

      all_edu_data: {
        fields: [
          {
            id: '',
            university: '',
            edu_degree_id: '',
            edu_status_id: '',
            edu_quality_id: '',
            year_end: '',
            specialty: '',
            document_number: '',
            files: [],
            previewFiles: [],
          },
        ],
  
        additional_education_fields: [
          {
            organization: '',
            name: '',
            year_end: '',
          },
        ],
      },

      files: [],

      submitDisabled: false,
      isSaved: false,
      isLoading: false,
    };
  },

  validations: {
    all_edu_data: {
      fields: {
        required,
        $each: {
          edu_degree_id: {required},
          edu_quality_id: {required},
          edu_status_id: {required},
          university: {required},
          year_end: {required: requiredIf(model => model.edu_status_id !== 11)},
          specialty: {required},
          document_number: {},
          files: {},
          previewFiles: {},
        },
      },
      additional_education_fields: {
        $each: {
          organization: {
            required: requiredIf(function(model) {
              return model.name !== '' || model.year_end !== '';
            }),
          },
          name: {
            required: requiredIf(function(model) {
              return model.organization !== '' || model.year_end !== '';
            }),
          },
          year_end: {
            required: requiredIf(function(model) {
              return model.organization !== '' || model.name !== '';
            }),
          },
        },
      },
    }
  },

  methods: {
    eduCanHaveDocumentNumber(edu) {
      return edu.edu_status_id && edu.edu_status_id === 8;
    },
    addSingle: function() {
      this.all_edu_data.fields.push(Vue.util.extend({}, this.education));
    },

    removeSingle: function(index, id) {
      if (id) {
        this.$http.delete(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/education/${id}`).then((resolve) => {
          if (resolve.data.data.education.length !== 0) {
            const data = [...resolve.data.data.education];
            this.all_edu_data.fields = [...this.addPreviews(data)];
          } else {
            Vue.delete(this.all_edu_data.fields, index);
          }
        }).catch(error => {
          console.log(error);
        });
      } else {
        Vue.delete(this.all_edu_data.fields, index);
      }
    },

    addAdditionalEducation: function() {
      this.all_edu_data.additional_education_fields.push(Vue.util.extend({}, this.additional_education));
    },

    removeAdditionalEducation(index) {
      Vue.delete(this.all_edu_data.additional_education_fields, index);
    },

    addPreviews: function(fields) {
      return fields.map((item) => {
        let obj = Object.assign({}, item);
        obj.previewFiles = [...obj.files];
        return obj;
      });
    },

    sendData: function() {
      this.submitDisabled = true;
      this.isSaved = false;
      this.isLoading = true;

      const data = this.all_edu_data.fields.map((item) => {
        let obj = Object.assign({}, item);
        obj.user_id = this.personalInfo.id;

        return obj;
      });

      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/education`,
          {
            education: data,
            additional_education: this.all_edu_data.additional_education_fields.filter(
                edu => edu.name !== '' && edu.organization !== '' && edu.year_end !== ''),
          },
      ).then((response) => {
        if (response.status === 201) {
          this.isSaved = true;
          this.isLoading = false;
        }
      }).catch(() => {
        this.submitDisabled = false;
        this.isLoading = false;
      });
    },
  },
};
</script>
