<template>
  <div class="profile__container">
    <h4 class="title">
      Оcновная информация
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <TextInput class="invert"
                     :class="$v.fields.full_name.$invalid ? 'error' : ''"
                     type="text"
                     placeholder="Полное наименование учебного заведения"
                     v-model="fields.full_name"
                     :isLabel="false"
                     :required="true"/>

        </div>

        <div class="col-50">
          <TextInput class="invert"
                     :class="$v.fields.short_name.$invalid ? 'error' : ''"
                     type="text"
                     placeholder="Краткое наименование учебного заведения"
                     v-model="fields.short_name"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-50">
          <Select :array="cities.data"
                  :class="$v.fields.city_id.$invalid ? 'error' : ''"
                  placeholder="Город"
                  :pre-selected="fields.city_id"
                  @select="fields.city_id = $event"/>
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <Select :array="inst_type"
                  :class="$v.fields.inst_type_id.$invalid ? 'error' : ''"
                  placeholder="Тип учебного заведения"
                  :pre-selected="fields.inst_type_id"
                  @select="fields.inst_type_id = $event"/>
        </div>

        <div class="col-50">
          <Select :array="inst_diploma"
                  placeholder="Категория диплома"
                  :pre-selected="fields.inst_diploma_id"
                  @select="fields.inst_diploma_id = $event"/>
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
                     placeholder="Количество учащихся"
                     v-model="fields.count_students"
                     :isLabel="false"
                     :required="true"
                     inputMask="######"/>
        </div>

        <div class="col-50">
          <Select :array="employment_assistance_data"
                  placeholder="Вступительные испытания"
                  :pre-selected="fields.entrance_test"
                  @select="fields.entrance_test = $event"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="Средний балл по ЕГЭ"
                     v-model="fields.avg_ege"
                     :isLabel="false"
                     :required="true"
                     inputMask="#########"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="Средний балл по ОГЭ"
                     v-model="fields.avg_oge"
                     :isLabel="false"
                     :required="true"
                     inputMask="#########"/>
        </div>

        <div class="col-100">
          <TextInput class="invert"
                     type="text"
                     placeholder="Процент поступивших в ВУЗЫ\СПО на бюджетные места"
                     v-model="fields.success_percent"
                     :isLabel="false"
                     :required="true"
                     inputMask="#########"/>
        </div>

        <div class="col-50">
          <Checkbox :id="'rating_show'"
                    label="Отображать рейтинг"
                    :description="`Текущий рейтинг — ${fields.rating.personal}`"
                    :margin="24"
                    :checked="fields.rating_show"
                    @change="fields.rating_show = $event"
          />
        </div>
      </div>
    </section>

    <h4 class="title">
      Контакты
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <TextEditor
              style="margin-bottom: 20px;"
              v-model="fields.contact_description"
          />
        </div>
      </div>

      <div class="profile__contacts" v-for="(block, key) in $v.fields.contacts.$each.$iter" :key="key">
        <div class="row">
          <div class="col-100">
            <TextInput class="invert"
                       :class="block.name.$invalid ? 'error' : ''"
                       type="text"
                       placeholder="Наименование контакта"
                       v-model="block.name.$model"
                       :isLabel="false"
                       :required="true"/>

            <AddressInput
                v-model="block.address.$model"
                :coords.sync="block.coords.$model"
                :invalid="block.address.$invalid"
            />
          </div>
        </div>

        <div class="row">
          <div class="col-50">
            <div class="profile__contacts-add">
              <TextInput class="invert"
                         :class="block.phones.$invalid ? 'error' : ''"
                         type="text"
                         placeholder="Телефон"
                         v-model="fields.contacts[key].currentPhone"
                         :isLabel="false"
                         :required="true"
                         inputMask="+# ### ###-##-##"
                         :margin="0"/>

              <Button @click.native="addContact(fields.contacts[key].currentPhone, key, 'phone')" class="btn--blue">
                <Icon xlink="plus"
                      viewport="0 0 16 16"/>
              </Button>
            </div>

            <div class="profile__contacts-added" v-if="block.phones.$model.length">
              <div class="profile__contacts-added-item" v-for="(phone, childKey) in block.phones.$model"
                   :key="childKey">
                <Icon xlink="phone"
                      viewport="0 0 16 16"/>

                <span>{{ phone }}</span>

                <Button @click.native="removeContact(childKey, key, 'phone')" class="link-svg">
                  <Icon xlink="delete"
                        viewport="0 0 16 16"/>
                </Button>
              </div>
            </div>
          </div>

          <div class="col-50">
            <div class="profile__contacts-add">
              <TextInput class="invert"
                         :class="block.emails.$invalid ? 'error' : ''"
                         type="text"
                         placeholder="Email"
                         v-model="fields.contacts[key].currentEmail"
                         :isLabel="false"
                         :required="true"
                         :margin="0"/>
              <Button @click.native="addContact(fields.contacts[key].currentEmail, key, 'email')" class="btn--blue">
                <Icon xlink="plus"
                      viewport="0 0 16 16"/>
              </Button>
            </div>

            <div class="profile__contacts-added" v-if="block.emails.$model.length">
              <div class="profile__contacts-added-item" v-for="(email, childKey) in block.emails.$model"
                   :key="childKey">
                <Icon xlink="email"
                      viewport="0 0 16 16"/>

                <span>{{ email }}</span>

                <Button @click.native="removeContact(childKey, key, 'email')" class="link-svg">
                  <Icon xlink="delete"
                        viewport="0 0 16 16"/>
                </Button>
              </div>
            </div>
          </div>
        </div>
      </div>

      <Button @click.native="addSingle" class="btn--light">
        <Icon xlink="plus"
              viewport="0 0 16 16"/>
        Добавить блок контактов
      </Button>
    </section>

    <section class="profile__section">
      <div class="row">
        <div class="col-50">
          <TextInput class="invert"
                     :class="$v.fields.website.$invalid ? 'error' : ''"
                     type="text"
                     placeholder="Сайт"
                     v-model="fields.website"
                     :isLabel="false"
                     :required="true"/>
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="facebook.com/"
                     v-model="fields.link_fb"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="vk.com/"
                     v-model="fields.link_vk"
                     :isLabel="false"
                     :required="true"/>
        </div>
      </div>
    </section>

    <h4 class="title">
      Реквизиты
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="КПП"
                     v-model="fields.kpp"
                     :isLabel="false"
                     :required="true"
                     inputMask="#########"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="ОГРН"
                     v-model="fields.ogrn"
                     :isLabel="false"
                     :required="true"
                     inputMask="#############"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="Банк"
                     v-model="fields.bank"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="ИНН банка"
                     v-model="fields.bank_inn"
                     :isLabel="false"
                     :required="true"
                     inputMask="##########"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="Расчётный счёт"
                     v-model="fields.account"
                     :isLabel="false"
                     :required="true"
                     inputMask="####################"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="Корреспондентский счёт"
                     v-model="fields.account_corr"
                     :isLabel="false"
                     :required="true"
                     inputMask="####################"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="БИК"
                     v-model="fields.bik"
                     :isLabel="false"
                     :required="true"
                     inputMask="#########"/>
        </div>

        <div class="col-50">
          <TextInput class="invert"
                     type="text"
                     placeholder="ОКТМО"
                     v-model="fields.oktmo"
                     :class="$v.fields.oktmo.$invalid ? 'error' : ''"
                     inputMask="###########"
                     :isLabel="false"
                     :required="true"/>
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
      </div>
    </section>
  </div>
</template>

<script>
import Vue from 'vue';
import {minLength, required} from 'vuelidate/lib/validators';
import AddressInput from '@/components/InputComponents/AddressInput';

export default {
  name: 'HighSchoolMainInfoForm',
  components: {AddressInput},
  computed: {
    orgInfo: function() {
      return this.$organization;
    },
    inst_type: function() {
      return this.$dictionaries.inst_type;
    },
    inst_diploma: function() {
      return this.$dictionaries.inst_diploma;
    },
    cities: function() {
      return this.$cities;
    },
  },

  created() {
    if (this.orgInfo.length !== 0) {
      this.fields = {...this.orgInfo[0]};

      if (this.fields.contacts === null) {
        this.fields.contacts = [...this.contact];
      }
    }
  },

  data: function() {
    return {
      fields: {
        id: '',

        full_name: '',
        short_name: '',

        rating_show: false,

        city_id: null,
        inst_type_id: null,
        inst_diploma_id: null,
        entrance_test: null,

        description: '',
        count_students: '',
        avg_ege: '',
        avg_oge: '',
        success_percent: '',

        contact_description: '',

        contacts: [
          {
            name: '',
            address: '',
            coords: [],
            phones: [],
            emails: [],
            currentEmail: '',
            currentPhone: '',
          },
        ],

        website: '',
        link_fb: '',
        link_vk: '',

        bank: '',
        bank_inn: '',
        account: '',
        account_corr: '',
        bik: '',
        kpp: '',
        oktmo: '',
      },

      contact: {
        name: '',
        address: '',
        coords: [],
        phones: [],
        emails: [],
        currentEmail: '',
        currentPhone: '',
      },

      submitDisabled: false,
      isSaved: false,
      isLoading: false,

      employment_assistance_data: [
        {
          name: 'Нет',
          id: 0,
        },
        {
          name: 'Да',
          id: 1,
        },
      ],
    };
  },

  validations: {
    fields: {
      full_name: {required},
      short_name: {required},
      description: {required},
      city_id: {required},
      inst_type_id: {required},

      contacts: {
        $each: {
          name: {required},
          address: {required},
          coords: {},
          phones: {required},
          emails: {required},
        },
      },

      website: {},
      oktmo: {minLength: minLength(11)},
    },
  },

  methods: {
    addSingle: function() {
      this.fields.contacts.push(Vue.util.extend({}, this.contact));
    },

    addContact: function(item, index, name) {
      if (name === 'phone' && item.length > 5) {
        this.fields.contacts[index].phones.push(item);
        this.fields.contacts[index].currentPhone = '';
      }
      if (name === 'email' && item.length > 5) {
        this.fields.contacts[index].emails.push(item);
        this.fields.contacts[index].currentEmail = '';
      }
    },

    removeContact: function(childIndex, parentIndex, name) {
      if (name === 'phone') {
        this.fields.contacts[parentIndex].phones.splice(childIndex, 1);
      }
      if (name === 'email') {
        this.fields.contacts[parentIndex].emails.splice(childIndex, 1);
      }
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

      if (id) {
        this.$http.put(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${id}`, {...this.fields}).
            then((response) => {
              if (response.status === 200) this.isSaved = true;
              this.isLoading = false;
              this.$store.dispatch('GET_ORG_DATA_FROM_SERVER').then(() => {
              });
            }).
            finally(() => {
              this.submitDisabled = false;
              this.isLoading = false;
            });
      } else {
        this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions`, {...this.fields}).
            then((response) => {
              if (response.status === 201) this.isSaved = true;
              this.isLoading = false;
            }).
            finally(() => {
              this.submitDisabled = false;
              this.isLoading = false;
            });
      }
    },
  },
};
</script>
