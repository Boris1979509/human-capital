<template>
  <div class="profile__container">
    <h4 class="title">
      Персональная информация
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-50">
          <TextInput
              class="invert"
              :class="$v.fields.first_name.$invalid ? 'error' : ''"
              type="text"
              placeholder="Имя"
              v-model="fields.first_name"
              :isLabel="false"
              :required="true"
          />

          <TextInput
              class="invert"
              :class="$v.fields.last_name.$invalid ? 'error' : ''"
              type="text"
              placeholder="Фамилия"
              v-model="fields.last_name"
              :isLabel="false"
              :required="true"
          />

          <TextInput
              class="invert"
              type="text"
              placeholder="Отчество"
              v-model="fields.middle_name"
              :isLabel="false"
              :required="true"
          />
          <div class="row">
            <div class="col-50">
              <Select
                  :array="sex"
                  :class="$v.fields.sex.$invalid ? 'error' : ''"
                  placeholder="Пол"
                  :pre-selected="fields.sex"
                  @select="fields.sex = $event"
              />
            </div>

            <div class="col-50">
              <rir-date-picker
                  v-model="fields.birthday"
                  label="Дата рождения"
                  :readonly="false"
                  isTimeZone
              />
            </div>

            <div class="col-100" v-if="$v.fields.birthday.$invalid">
              <span class="errors">Введите корректную дату в формате ДД.ММ.ГГГГ</span>
            </div>
          </div>
        </div>

        <div class="col-50">
          <Select
              :array="nationalities"
              :class="$v.fields.nationality_id.$invalid ? 'error' : ''"
              placeholder="Гражданство"
              :pre-selected="fields.nationality_id"
              @select="fields.nationality_id = $event"
          />

          <Select
              :array="cities.data"
              :class="$v.fields.city_id.$invalid ? 'error' : ''"
              placeholder="Город проживания"
              :pre-selected="fields.city_id"
              @select="fields.city_id = $event"
          />

          <Select
              :array="country"
              :class="$v.fields.country_id.$invalid ? 'error' : ''"
              placeholder="Страна проживания"
              :pre-selected="fields.country_id"
              @select="fields.country_id = $event"
          />
        </div>
      </div>
    </section>

    <h4 class="title">
      Паспортные данные
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-25">
          <TextInput
              class="invert"
              type="text"
              placeholder="Документ"
              :isLabel="false"
              :required="false"
              :disabled="true"
          />
        </div>

        <div class="col-25">
          <TextInput
              class="invert"
              type="text"
              placeholder="Серия"
              v-model="fields.document_series"
              :isLabel="false"
              :required="false"
              :disabled="true"
              inputMask="## ##"
          />
        </div>

        <div class="col-25">
          <TextInput
              class="invert"
              type="text"
              placeholder="Номер"
              v-model="fields.document_number"
              :isLabel="false"
              :required="false"
              :disabled="true"
              inputMask="######"
          />
        </div>

        <div class="col-25">
          <TextInput
              class="invert"
              type="text"
              placeholder="Дата выдачи"
              v-model="fields.document_date"
              :isLabel="false"
              :required="false"
              :disabled="true"
          />
        </div>

        <div class="col-25">
          <TextInput
              class="invert"
              type="text"
              placeholder="ИНН"
              v-model="fields.inn"
              :isLabel="false"
              :required="false"
              :disabled="true"
          />
        </div>

        <div class="col-25">
          <TextInput
              class="invert"
              type="text"
              placeholder="СНИЛС"
              v-model="fields.snills"
              :isLabel="false"
              :required="false"
              :disabled="true"
              inputMask="##-########"
          />
        </div>
      </div>
    </section>

    <h4 class="title">
      Контакты
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-50">
          <TextInput
              class="invert"
              :class="$v.phone.$invalid ? 'error' : ''"
              type="tel"
              placeholder="Телефон"
              v-model="phone"
              :isLabel="false"
              :required="true"
              inputMask="+# ### ###-##-##"
          />
        </div>

        <div class="col-50">
          <TextInput
              class="invert"
              :class="$v.fields.email.$invalid ? 'error' : ''"
              type="email"
              placeholder="E-mail"
              v-model="fields.email"
              :isLabel="false"
              :required="true"
              :disabled="true"
          />
        </div>

        <div class="col-50">
          <TextInput
              class="invert"
              type="text"
              placeholder="vk.com/"
              v-model="fields.link_vk"
              :isLabel="false"
              :required="true"
          />
        </div>

        <div class="col-50">
          <TextInput
              class="invert"
              type="text"
              placeholder="facebook.com/"
              v-model="fields.link_fb"
              :isLabel="false"
              :required="true"
          />
        </div>
      </div>
    </section>

    <h4 class="title">
      Дополнительная информация
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <TextInput
              :textarea="true"
              class="invert"
              placeholder="Обо мне"
              v-model="fields.description"
              :isLabel="false"
              :required="true"
          />
        </div>
      </div>
    </section>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <Button
              @click.native="submit"
              :disabled="this.$v.$invalid"
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
      :observable-fields.sync="fields"
      :save-func="submit"
      :is-saved="isSaved"
    />
  </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators';
import phone from '../../Validators/phoneValidator';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'PersonalInfoForm',

  components: {
    formAutoSaver,
  },

  computed: {
    cities: function() {
      return this.$cities;
    },
  },

  created() {
    this.fields = {...this.$personal?.personal};
    this.fields.email = this.$personal?.email;
    this.phone = this.$personal?.phone;
    this.fields.document_date = this.dateFormat(
        this.$personal?.personal?.document_date,
        'receive',
    );
  },

  data: function() {
    return {
      fields: {},

      isSaved: false,
      isLoading: false,

      sex: [
        {
          id: 1,
          name: 'Мужской',
        },
        {
          id: 2,
          name: 'Женский',
        },
      ],

      country: [
        {
          id: 1,
          name: 'Россия',
        },
      ],

      nationalities: [
        {
          id: 1,
          name: 'РФ',
        },
      ],

      // по непонятным причинам если вложить телефон
      // в массив полей - то у него пропадает реактивность
      // видимо маска и валидатор конфликтуют между собой
      phone: '',
    };
  },

  validations: {
    fields: {
      first_name: {required},
      last_name: {required},
      sex: {required},
      birthday: {required},
      nationality_id: {required},
      city_id: {required},
      country_id: {required},
      email: {required},
    },
    phone: {required, phone},
  },

  methods: {
    submit() {
      this.isSaved = false;
      if (this.$v.$invalid) {
        return;
      }
      this.isLoading = true;
      let data = {
        ...this.fields,
        phone: this.phone,
      };
      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user`, data).then((response) => {
        if (response.status === 201) this.isSaved = true;
      }).finally(() => {
        this.isLoading = false;
      });
    },

    dateFormat(date, type) {
      if (type === 'send' && date !== null && date !== undefined) {
        const givenDate = date.split('.');
        const dateToSend = `${givenDate[2]}/${givenDate[1]}/${givenDate[0]}`;
        return dateToSend;
      }
      if (type === 'receive' && date !== null && date !== undefined) {
        const givenDate = date.split('/');
        const dateToReceive = `${givenDate[2]}.${givenDate[1]}.${givenDate[0]}`;
        return dateToReceive;
      }

      return null;
    },
  },
};
</script>
