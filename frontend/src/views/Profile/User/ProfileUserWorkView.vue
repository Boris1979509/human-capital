<template>
  <ProfileUserWrapper>
    <h4 class="title">
      Ключевые навыки
    </h4>

    <div class="row">
      <div class="col-100">
        <TagsCloud
            :auto-complete="true"
            :tags="all_work_data.skills"
            :class="$v.all_work_data.skills.$invalid ? 'error' : ''"
            @updateTags="all_work_data.skills.push($event)"
            placeholder="Введите навыки, например, Microsoft Office"
        />
      </div>
    </div>

    <h4 class="title">
      Профессиональные качества
    </h4>

    <div class="row">
      <div class="col-100">
        <TagsCloud
            :tags="all_work_data.qualities"
            :class="$v.all_work_data.qualities.$invalid ? 'error' : ''"
            @updateTags="all_work_data.qualities.push($event)"
            placeholder="Введите навыки, например, стрессоустойчивость"
        />
      </div>
    </div>

    <h4 class="title">
      Места работы
    </h4>
    <div v-for="(item, key) in $v.all_work_data.fields.$each.$iter" :key="key" class="profile__education">
      <section class="profile__section">
        <div class="place_of_work">
          <h5 class="title">
            Место работы {{ parseInt(key) + 1 }}
            <div
                class="remove_place_of_work"
                @click="remove_place_of_work(item.$model.id, key)"
            >
              <Icon xlink="delete_place_of_work" viewport="0 0 14 16"/>
            </div>
          </h5>
        </div>
        <div class="row">
          <div class="col-100">
            <TextInput
                class="invert"
                :class="item.company.$invalid ? 'error' : ''"
                type="text"
                placeholder="Название организации"
                v-model="item.company.$model"
                :isLabel="false"
                :required="true"
            />
          </div>

          <div class="col-50">
            <TextInput
                class="invert"
                :class="item.website.$invalid ? 'error' : ''"
                type="text"
                placeholder="Сайт организации"
                v-model="item.website.$model"
                :isLabel="false"
                :required="true"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-100">
            <TextInput
                class="invert"
                :class="item.position.$invalid ? 'error' : ''"
                type="text"
                placeholder="Должность"
                v-model="item.position.$model"
                :isLabel="false"
                :required="true"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-33">
            <Select
                :array="workYear"
                :class="item.year_begin.$invalid ? 'error' : ''"
                :pre-selected="
                item.year_begin.$model < workYear[0].name ||
                item.year_begin.$model > workYear[workYear.length - 1].name
                  ? null
                  : item.year_begin.$model
              "
                placeholder="Начало работы"
                @select="item.year_begin.$model = $event"
            />
          </div>

          <div class="col-33">
            <Select
                :array="workYear"
                :class="item.year_end.$invalid ? 'error' : ''"
                :pre-selected="
                item.year_end.$model < workYear[0].name ||
                item.year_end.$model > workYear[workYear.length - 1].name
                  ? null
                  : item.year_end.$model
              "
                placeholder="Окончание работы"
                @select="item.year_end.$model = $event"
                :disabled="item.until_now.$model"
            />
          </div>

          <div class="col-33 col-33--center">
            <Checkbox
                @change="
                item.until_now.$model = $event;
                $event ? (item.year_end.$model = '') : '';
              "
                :checked="item.until_now.$model"
                label="Работаю"
                :id="`isWork-${key}`"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-100">
            <TextEditor
                style="margin-bottom: 20px;"
                v-model="item.description.$model"
                :class="item.description.$invalid ? 'error' : ''"
            />
          </div>
        </div>
      </section>
    </div>
    <div class="profile__submit">
      <Button
          @click.native="addSingle"
          class="btn--light"
          style="margin-bottom: 40px;"
      >
        <Icon xlink="plus" viewport="0 0 16 16"/>

        {{
          this.all_work_data.fields.length
              ? 'Указать ещё одно место работы'
              : 'Указать место работы'
        }}
      </Button>
    </div>

    <h4 class="title resume">
      Резюме

      <div class="resume-generate" @click="getResume">
        <div class="form_resume">
          <Icon
              xlink="resume"
              viewport="0 0 14 16"
              style="display:block; margin-top: 10px;"
          />
        </div>
        <div class="resume-generate-text">Сформировать</div>
      </div>
      <p>
        Сформируйте резюме на основе заполненных данных или загрузите свое
        готовое резюме, чтобы иметь возможность откликаться на вакансии
      </p>
    </h4>

    <section class="profile__section" style="margin-bottom: 0;">
      <div class="row">
        <div class="col-100">
          <Upload
              :photos.sync="files"
              :upload-field-name="'files[]'"
              :preview="previewFiles"
          />
        </div>
      </div>
    </section>

    <section class="profile__education-submit" style="margin-top: 0;">
      <div class="row">
        <div class="col-100">
          <Button
              @click.native="sendData"
              :disabled="$v.$invalid"
              :is-success="isSaved"
              :is-spinner="isLoading"
              class="btn--blue"
          >
            {{ isSaved ? 'Сохранено' : 'Сохранить' }}
          </Button>
        </div>
      </div>
    </section>
    <formAutoSaver
      :observable-fields.sync="all_work_data"
      :save-func="sendData"
      :is-saved="isSaved"
    />
  </ProfileUserWrapper>
</template>

<script>
import Vue from 'vue';
import {required, requiredIf} from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'ProfileUserWorkView',

  components: {
    formAutoSaver,
  },

  computed: {
    personalInfo: function() {
      return this.$personal;
    },
    workYear: function() {
      return this.$createYearsRangeFromTo(1975, 2025);
    },
  },

  async mounted() {
    await this.$store.dispatch('GET_PERSONAL_DATA_FROM_SERVER');

    const isAvailable = this.personalInfo;

    this.all_work_data.fields = isAvailable && isAvailable.jobs && [...isAvailable.jobs];
    this.all_work_data.skills =
        (isAvailable &&
            isAvailable.personal &&
            isAvailable.personal.skills || []);
    this.all_work_data.qualities =
        (isAvailable &&
            isAvailable.personal &&
            isAvailable.personal.qualities || []);
    this.previewFiles = isAvailable &&
        isAvailable.job_files && [...isAvailable.job_files];
  },

  data: function() {
    return {
      IWork: {
        company: '',
        website: '',
        position: '',
        year_begin: null,
        year_end: null,
        until_now: false,
        description: '',
      },

      files: [],
      previewFiles: [],

      all_work_data: {
        skills: [],
        qualities: [],
        fields: [
          {
            company: '',
            website: '',
            position: '',
            year_begin: '',
            year_end: '',
            until_now: false,
            description: '',
          },
        ],
      },
      isLoading: false,
      isSaved: false,
    };
  },

  validations: {
    all_work_data: {
      skills: {required},
      qualities: {required},
      fields: {
        $each: {
          company: {required},
          website: {},
          position: {required},
          year_begin: {required},
          year_end: {required: requiredIf(model => !model.until_now)},
          until_now: {required},
          description: {required},
        },
      },
    },
  },

  methods: {
    addSingle: function() {
      this.all_work_data.fields.push(Vue.util.extend({}, this.IWork));
    },

    remove_place_of_work(id, key) {
      if (id) {
        this.$http.delete(
            `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/job/${id}`,
        ).then(({data: {data}, status}) => {
          if (status === 200 && data && data.jobs) {
            this.all_work_data.fields = [...data.jobs];
          }
        }).catch((err) => {
          console.error('Error during delete place of work', err);
        });
      }
      if (!isNaN(key)) {
        console.log(key);
        this.all_work_data.fields.splice(key, 1);
      }
    },

    sendData: function() {
      this.isLoading = true;
      this.isSaved = false;
      const self = this;

      const jobs = this.all_work_data.fields.map((item) => {
        return Object.assign({}, item);
      });

      const skills = self.all_work_data.skills;
      const qualities = self.all_work_data.qualities;

      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/job`, {
        skills,
        qualities,
        jobs,
        files: this.files,
      }).then((response) => {
        if (response.status === 201) {
          this.isSaved = true;
          this.isLoading = false;
        }
      }).catch(() => {
        this.submitDisabled = false;
      });
    },

    getResume() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/resume`, {
        responseType: 'blob',
      }).then((res) => {
        const url = window.URL.createObjectURL(
            new Blob([res.data], {type: 'application/pdf'}),
        );
        const link = document.createElement('a');
        link.href = url;
        link.setAttribute('target', '_blank');
        link.click();
      });
    },
  },
};
</script>
<style scoped lang="scss">
.place_of_work {
  margin-bottom: 24px;

  .remove_place_of_work {
    display: inline-block;
    margin-left: 25px;
    height: 16px;
    width: 14px;
    line-height: 1;
    cursor: pointer;
  }
}

.resume {
  .form_resume {
    display: inline-block;
    margin-left: 25px;
    height: 24px;
    width: 14px;
    line-height: 1;
    cursor: pointer;
  }

  .resume-generate {
    display: inline-block;
    height: 24px;
  }

  .resume-generate-text {
    display: inline-block;
    margin-left: 7px;
    height: 24px;
    font-size: 16px;
    cursor: pointer;
    line-height: 20px;
    text-align: right;
    color: #214eb0;
  }

  p {
    font-family: Golos;
    font-style: normal;
    font-weight: normal;
    font-size: 16px;
    line-height: 24px;
    color: rgba(4, 21, 62, 1);
    opacity: 0.72;
    margin-top: 16px;
  }
}
</style>
