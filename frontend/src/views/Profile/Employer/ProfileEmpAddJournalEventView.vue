<template>
  <div class="profile__container">
    <h4 class="title">
      {{ $route.params.target === 'edit' ? 'Редактировать' : 'Добавить' }}
      мероприятие
    </h4>

    <section class="profile__section">
      <UploadWithCrop
          :photos.sync="cover"
          stencil-component="rectangle-stencil"
          description="Выбрать обложку"
      />
    </section>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <TextInput
              class="invert"
              :class="$v.event.title.$invalid ? 'error' : ''"
              type="text"
              placeholder="Заголовок"
              :isLabel="false"
              :required="true"
              v-model="event.title"
          />
        </div>
      </div>
      <div class="row mb-20">
        <div class="col-50">
          <rir-date-picker
              v-model="event.date_start"
              :class="$v.event.date_start.$invalid ? 'error' : ''"
              label="Дата и время начала мероприятия"
              :readonly="false"
              isTime
          />
        </div>
        <div class="col-50">
          <Checkbox
              class="pt-10"
              :id="'someDaysDuration'"
              :checked="someDaysDuration"
              :label="'Длится несколько дней'"
              @change="someDaysDuration = $event"
          />
        </div>
      </div>
      <div v-if="someDaysDuration" class="row mb-20">
        <div class="col-50">
          <rir-date-picker
              v-model="event.date_end"
              :class="$v.event.date_end.$invalid ? 'error' : ''"
              label="Дата и время окончания мероприятия"
              :readonly="false"
              isTime
          />
        </div>
      </div>
      <div class="row">
        <div class="col-100">
          <AddressInput
              v-model="event.address"
              :coords.sync="event.coords"
              :invalid="$v.event.address.$invalid"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <TextInput
              class="invert"
              type="text"
              :class="$v.event.phone.$invalid ? 'error' : ''"
              placeholder="Телефон"
              :isLabel="false"
              :required="true"
              v-model="event.phone"
          />
        </div>
      </div>
      <div class="row" style="margin-bottom: 20px;">
        <div class="col-100">
          <TextEditor
              v-model="event.text"
              :class="$v.event.text.$invalid ? 'error' : ''"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <MultiSelect
              :array="dictionaries.inst_auditory_journal"
              :pre-selected="event.target_audience"
              :class="$v.event.target_audience.$invalid ? 'error' : ''"
              placeholder="Целевая аудитория"
              @select="event.target_audience = $event"
          />
        </div>
        <div class="col-50">
          <MultiSelect
              :array="dictionaries.inst_age"
              :pre-selected="event.participants_age"
              :class="$v.event.participants_age.$invalid ? 'error' : ''"
              placeholder="Возраст участников"
              @select="event.participants_age = $event"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-100">
          <TagsCloud
              :tags="event.tags"
              :class="$v.event.tags.$invalid ? 'error' : ''"
              @updateTags="event.tags.push($event)"
              placeholder="Тематика"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-100">
          <Upload :photos.sync="images" :upload-field-name="'files[]'"/>
        </div>
      </div>
    </section>
    <section class="profile__section">
      <div v-for="(speaker, idx) in $v.event.speakers.$each.$iter" :key="idx">
        <h4 class="title" :key="idx">
          Спикер {{ parseInt(idx) + 1 }}
          <div @click="removeSpeaker(idx)" class="remove_speaker">
            <Icon xlink="delete" viewport="0 0 16 16"/>
          </div>
        </h4>
        <div class="row">
          <div class="col-100">
            <TextInput
                class="invert"
                type="text"
                placeholder="Спикер"
                :class="speaker.name.$invalid ? 'error' : ''"
                v-model="speaker.name.$model"
                :isLabel="false"
                :required="true"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-100">
            <TextInput
                class="invert"
                type="text"
                placeholder="Должность"
                :class="speaker.position.$invalid ? 'error' : ''"
                v-model="speaker.position.$model"
                :isLabel="false"
                :required="true"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-100">
            <Upload
                :photos.sync="event.speakers[idx].avatar"
                :single-file="true"
            />
          </div>
        </div>
      </div>
      <div class="row">
        <div class="col-100">
          <Button
              @click.native="addSpeaker"
              :disabled="false"
              class="btn--light"
          >
            Добавить спикера
          </Button>
        </div>
      </div>
    </section>
    <section class="profile__section">
      <div class="row mb-24">
        <div class="col-100">
          <Checkbox
              label="Включить онлайн регистрацию"
              id='is_registration_required'
              :checked="event.is_registration_required"
              @change="event.is_registration_required = $event"
          />
        </div>
        <div class="col-50">
          <rir-date-picker
              v-model="event.registration_available_till"
              label="Период регистрации"
              :readonly="false"
              isTime
          />
        </div>
        <div class="col-50">
          <TextInput
              v-model="event.available_registration_slots"
              class="invert"
              type="text"
              placeholder="Количество мест"
              inputMask="######"
          />
        </div>
        <div class="col-100">
          <EventRegistrationQuestions v-model="event.registration_questions"/>
        </div>
        <div class="col-100">
          <Checkbox
              id='is_registration_auto'
              label="Автоматически одобрять заявки по истечению срока рассмотрения"
              :checked="event.is_registration_auto"
              @change="event.is_registration_auto = $event"
          />
        </div>
        <div class="col-100" v-if="event.is_registration_auto">
          <Select
              placeholder="Срок рассмотрения"
              :array="autoRegistrationPeriods"
              :pre-selected="event.registration_auto_period"
              @select="event.registration_auto_period = `${$event}`"
          />
        </div>
        <div class="col-100">
          <Checkbox
              id='is_registration_reminders_enabled'
              label="Напопинать зарегистрировавшимся о мероприятии по электронной почте"
              :checked="event.is_registration_reminders_enabled"
              @change="event.is_registration_reminders_enabled = $event"
          />
        </div>
        <div class="col-100" v-if="event.is_registration_reminders_enabled">
          <Select
              placeholder="Частота напоминаний"
              :array="remindersPeriod"
              :pre-selected="event.registration_reminder_periods"
              @select="event.registration_reminder_periods = [$event]"
          />
        </div>
      </div>
    </section>
    <section class="profile__section">
      <div class="row mb-24">
        <div class="col-50">
          <Checkbox
              :id="'allowComments'"
              :checked="event.comments_enabled"
              :label="'Открыть комментарии'"
              @change="event.comments_enabled = $event"
          />
        </div>
      </div>
      <div class="row">
        <div class="col-50">
          <Checkbox
              :id="'is_published'"
              :checked="event.is_published"
              :label="'Публиковать на сайте'"
              @change="event.is_published = $event"
          />
        </div>
      </div>
    </section>
    <div class="row">
      <div class="col-100">
        <Button
            @click.native="addEvent"
            :is-success="isSaved"
            :is-spinner="isLoading"
            :disabled="$v.$invalid"
            class="btn--blue"
        >
          {{ isSaved ? 'Сохранено' : 'Сохранить' }}
        </Button>
      </div>
    </div>
    <formAutoSaver
        :observable-fields.sync="event"
        :save-func="addEvent"
        :is-saved="isSaved"
    />
  </div>
</template>

<script>
import MultiSelect from '@/components/InputComponents/MultiSelect';
import formAutoSaver from '@/components/formAutoSaver';
import {required, requiredIf} from 'vuelidate/lib/validators';
import AddressInput from '@/components/InputComponents/AddressInput';
import EventRegistrationQuestions from '@/views/Profile/Organization/EventRegistrationQuestions';

export default {
  name: 'ProfileEmpAddJournalEventView',

  components: {
    EventRegistrationQuestions,
    AddressInput,
    MultiSelect,
    formAutoSaver,
  },

  computed: {
    empInfo: function() {
      return this.$employer;
    },
    dictionaries: function() {
      return this.$dictionaries;
    },
    isDateEndRequired() {
      return this.someDaysDuration;
    },
  },
  data: function() {
    return {
      signal: null,
      someDaysDuration: false,
      inst_auditory_journal: [],
      inst_age: [],
      images: [],
      cover: [],
      preview: [],
      IEvent: {
        title: '',
        slug: '/',
        text: '',
        type: 3,
        date_start: '',
        date_end: '',
        address: '',
        coords: [],
        phone: '',
        target_audience: [],
        participants_age: [],
        tags: [],
        speakers: [],
        is_published: true,
        comments_enabled: true,
      },
      event: {
        title: '',
        slug: '/',
        text: '',
        type: 3,
        date_start: '',
        date_end: '',
        address: '',
        coords: [],
        phone: '',
        target_audience: [],
        participants_age: [],
        tags: [],
        speakers: [],
        is_published: true,
        comments_enabled: true,
      },
      ISpeaker: {
        name: '',
        position: '',
        avatar: '',
      },
      isLoading: false,
      isDisabled: false,
      isSaved: false,
      autoRegistrationPeriods: [
        {
          id: 0,
          name: 'Сразу',
        },
        {
          id: 1,
          name: '1 час',
        },
        {
          id: 8,
          name: '8 часов',
        },
      ],
      remindersPeriod: [
        {
          id: 1,
          name: 'За день',
        },
        {
          id: 2,
          name: 'За час',
        },
        {
          id: 3,
          name: 'За день, за час',
        },
      ],
    };
  },
  validations: {
    someDaysDuration: {},
    event: {
      title: {required},
      date_start: {required},
      date_end: {
        required: requiredIf(function() {
          return this.isDateEndRequired;
        }),
      },
      address: {required},
      phone: {required},
      text: {required},
      target_audience: {required},
      participants_age: {required},
      tags: {required},
      speakers: {
        $each: {
          name: {required},
          position: {required},
        },
      },
    },
  },
  watch: {
    empInfo(val) {
      if (val.id) {
        this.orgId = val.id;
        this.event.address =
            (val && val && val.contacts && val.contacts &&
                val.contacts[0].address) ||
            '';
      }
    },
    event: {
      handler: function(val) {
        if (JSON.stringify(val) === JSON.stringify(this.IEvent))
          console.log('equal');
        else this.$v.$touch();
      },
      deep: true,
    },
    $v: {
      handler: function(val) {
        if (val.$invalid && val.$dirty) {
          this.isSaved = false;
        }
      },
      deep: true,
    },
  },
  methods: {
    clearData() {
      this.event = {...this.IEvent};
    },
    addEvent() {
      this.isLoading = true;
      console.log(this.event.date_start);
      this.$http.post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/${this.empInfo.id}/journal/`,
          Object.assign(this.event, {
            date_start: this.event.date_start ? new Date(+this.event.date_start) : null,
            date_end: this.event.date_end ? new Date(+this.event.date_end) : null,
            registration_available_till: this.event.registration_available_till ?
                new Date(+this.event.registration_available_till) :
                null,
            images: this.images,
            cover: this.cover,
          }),
      ).then((response) => {
        if (response.status === 201) {
          this.isLoading = false;
          this.isSaved = true;
          this.isDisabled = true;
          // this.clearData();
          // this.$v.$reset();
          // this.$router.push({name: 'ProfileOrgJournalListView'});
          this.$router.push({name: 'ProfileEmpEditJournalEventView', params: {id: response.data.id, isSaved: true}});
        } else throw Error('error occured while journal event adding');
      }).catch((err) => {
        this.isLoading = false;
        console.log(err);
      });
    },
    addSpeaker() {
      let speaker = {...this.ISpeaker};
      this.event.speakers.push(speaker);
    },
    removeSpeaker(idx) {
      this.event.speakers.splice(idx, 1);
    },
  },
};
</script>

<style lang="scss" scoped>
// commom util styles
.relative {
  position: relative;
}

.remove_speaker {
  display: inline-block;
  margin-left: 25px;
  opacity: 0.32;
  cursor: pointer;

  &:hover {
    opacity: 1;
  }

  .icon {
    position: static;
  }
}

.pt-10 {
  padding-top: 10px;
}

.mb-24 {
  margin-bottom: 24px;
}

.mb-20 {
  margin-bottom: 20px;
}

.no-padding {
  padding: 0;
}

.icon {
  position: absolute;
  top: 12px;
  right: 28px;
}

//overwrite styles
/deep/ .rir-input__label {
  color: var(--main-color-dark-trans-light) !important;
}

/deep/ .rir-input__input > input {
  color: var(--main-color-dark-trans-hard);
}
</style>
