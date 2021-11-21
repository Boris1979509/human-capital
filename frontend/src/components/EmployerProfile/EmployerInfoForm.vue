<template>
  <div class="profile__container">
    <h4 class="title">
      Оcновная информация
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <TextInput class="invert"
                     :class="$v.fields.name.$invalid ? 'error' : ''"
                     type="text"
                     placeholder="Полное наименование организации"
                     v-model="fields.name"
                     :isLabel="false"
                     :required="true"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <Select :array="type_branch"
                  :class="$v.fields.branch_id.$invalid ? 'error' : ''"
                  placeholder="Отрасль"
                  :pre-selected="fields.branch_id"
                  @select="fields.branch_id = $event"/>
        </div>

        <div class="col-50">
          <TextInput v-if="fields.branch_id === 100"
                     class="invert"
                     :class="$v.fields.branch_name.$invalid ? 'error' : ''"
                     placeholder="Название отрасли"
                     v-model="fields.branch_name"
                     :isLabel="false"
                     :required="true"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <TextInput :textarea="true"
                     class="invert"
                     :class="$v.fields.description.$invalid ? 'error' : ''"
                     placeholder="О компании"
                     v-model="fields.description"
                     :isLabel="false"
                     :required="true"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <Upload :photos.sync="fields.images" :upload-field-name="'files[]'" :preview="preview"/>
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <TextInput class="invert"
                     :class="$v.fields.count_employees.$invalid ? 'error' : ''"
                     placeholder="Количество сотрудников"
                     v-model="fields.count_employees"
                     :isLabel="false"
                     :required="true"
                     :margin="32"
          />
        </div>

        <div class="col-50">
          <Checkbox :id="'is_internship'"
                    label="Есть стажировки"
                    :margin="32"
                    :checked="fields.is_internship"
                    @change="fields.is_internship = $event"
          />
        </div>

        <div class="col-100">
          <Checkbox :id="'is_open_vacancy'"
                    label="Показывать количество открытых вакансий"
                    :margin="32"
                    :checked="fields.show_vacancies_count"
                    @change="fields.show_vacancies_count = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <Select :array="cities.data"
                  :class="$v.fields.city_id.$invalid ? 'error' : ''"
                  placeholder="Город"
                  :pre-selected="fields.city_id"
                  @select="fields.city_id = $event"
          />
        </div>
        <div class="col-50">
          <TextInput class="invert"
                     :class="$v.fields.website.$invalid ? 'error' : ''"
                     placeholder="Сайт"
                     v-model="fields.website"
                     :isLabel="false"
                     :required="true"
                     :margin="32"
          />
        </div>
        <div class="col-100">
          <AddressInput
              v-model="fields.address"
              :coords.sync="fields.coords"
              :invalid="$v.fields.address.$invalid"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-100">
          <Checkbox :id="'show_contacts'"
                    label="Показывать контакты для связи"
                    :margin="32"
                    :checked="fields.show_contacts"
                    @change="fields.show_contacts = $event"
          />
        </div>
      </div>

      <div class="profile__contacts" v-for="(block, key) in $v.fields.contacts.$each.$iter" :key="key">
        <div class="profile__section-header">
          <h5 class="title">
            Контакты для связи {{ parseInt(key) + 1 }}
          </h5>

          <Button @click.native="removeSingle(key)" class="link-svg" v-if="fields.contacts.length > 1">
            <Icon xlink="delete"
                  viewport="0 0 16 16"/>
          </Button>
        </div>

        <div class="row">
          <div class="col-100">
            <TextInput class="invert"
                       :class="block.name.$invalid ? 'error' : ''"
                       type="text"
                       placeholder="Наименование контакта"
                       v-model="block.name.$model"
                       :isLabel="false"
                       :required="true"/>
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
        <div class="col-100">
          <Button @click.native="sendData" :is-success="isSaved" :is-spinner="isLoading" :disabled="$v.$invalid"
                  class="btn--blue">
            {{ isSaved ? 'Сохранено' : 'Сохранить' }}
          </Button>
        </div>
      </div>
    </section>
    <formAutoSaver
      :observable-fields.sync="fields"
      :save-func="sendData"
      :is-saved="isSaved"
    />
  </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators';
import Vue from 'vue';
import formAutoSaver from '@/components/formAutoSaver';
import AddressInput from '@/components/InputComponents/AddressInput';

export default {
  name: 'EmployerInfoForm',

  components: {
    AddressInput,
    formAutoSaver,
  },

  computed: {
    employerInfo: function() {
      return this.$employer;
    },
    type_branch: function() {
      return this.$dictionaries.type_branch;
    },
    cities: function() {
      return this.$cities;
    },
  },

  created() {
    if (this.employerInfo.length !== 0) {
      this.fields = {...this.employerInfo};
      this.preview = this.employerInfo.images;

      if (this.fields.contacts === null) {
        this.fields.contacts = [...this.contact];
      }
    }
  },

  data: function() {
    return {
      preview: [],

      fields: {
        id: '',
        name: '',
        branch_id: null,
        branch_name: '',
        description: '',
        images: [],
        count_employees: '',
        show_vacancies_count: false,
        is_internship: false,
        show_contacts: false,
        city_id: null,
        address: '',
        coords: [],
        website: '',
        contacts: [
          {
            name: '',
            phones: {
              $each: {required},
            },
            emails: {
              $each: {required},
            },
            currentEmail: '',
            currentPhone: '',
          },
        ],
      },

      contact: {
        name: '',
        phones: [],
        emails: [],
        currentEmail: '',
        currentPhone: '',
      },

      isSaved: false,
      isLoading: false,
      submitDisabled: false,
    }
  },

  validations: {
    fields: {
      name: {required},
      branch_id: {required},
      description: {required},
      count_employees: {required},
      city_id: {required},
      address: {required},
      website: {},

      contacts: {
        $each: {
          name: {required},
          phones: {required},
          emails: {required},
        },
      },
    },
  },

  methods: {
    addSingle: function() {
      this.fields.contacts.push(Vue.util.extend({}, this.contact));
    },

    removeSingle: function(index) {
      Vue.delete(this.fields.contacts, index);
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

    sendData() {
      this.submitDisabled = true;
      this.isSaved = false;
      this.isLoading = true;

      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/${this.fields.id}`, {...this.fields}).then((response) => {
        if (response.status === 201) this.isSaved = true;
        this.isLoading = false;
        this.$store.dispatch('GET_EMPLOYER_DATA_FROM_SERVER').then(() => {
        });
      }).finally(() => {
        this.submitDisabled = false;
        this.isLoading = false;
      });
    },
  },
}
</script>
