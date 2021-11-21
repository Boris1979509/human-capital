<template>
  <div class="profile__container">
    <h4 class="title">
      Редактировать мероприятие
    </h4>

    <section class="profile__section">
      <UploadWithCrop
          :photos.sync="cover"
          :preview="previewCover"
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
        <div class="col-50 relative">
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
              :class="$v.event.phone.$invalid ? 'error' : ''"
              type="text"
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
          <Upload
              :photos.sync="images"
              :upload-field-name="'files[]'"
              :preview="preview"
          />
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
                v-model="speaker.name.$model"
                :class="speaker.name.$invalid ? 'error' : ''"
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
                v-model="speaker.position.$model"
                :class="speaker.position.$invalid ? 'error' : ''"
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
                :preview="speakersPreview[idx]"
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
            @click.native="editEvent"
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
      </div>
    </div>
    <modal ref="modalName">
      <template v-slot:header></template>

      <template v-slot:body>
        <p class="modal_body_text">Удалить мероприятие?</p>
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
        :observable-fields.sync="event"
        :save-func="editEvent"
        :is-saved="isSaved"
        :update-via-signal="true"
        :signal="signal"
    />
  </div>
</template>

<script>
import MultiSelect from '@/components/InputComponents/MultiSelect';
import {required, requiredIf} from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';
import AddressInput from '@/components/InputComponents/AddressInput';
import EventRegistrationQuestions from '@/views/Profile/Organization/EventRegistrationQuestions';

export default {
  name: 'ProfileEmpEditJournalEventView',

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
      someDaysDuration: false,
      inst_auditory_journal: [],
      inst_age: [],
      images: [],
      cover: [],
      speakersImages: [],
      preview: [],
      previewCover: [],
      speakersPreview: [],
      event: {
        title: '',
        slug: '/',
        text: '',
        type: 3,
        date_start: '',
        date_end: '',
        time_start: '',
        address: '',
        coords: [],
        phone: '',
        target_audience: [],
        participants_age: [],
        tags: [],
        speakers: [],
        is_published: false,
        comments_enabled: false,
      },
      ISpeaker: {
        name: '',
        position: '',
        avatar: '',
      },
      isDisabled: false,
      isSaved: false,
      signal: false,
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
  watch: {
    event: {
      handler: function() {
        this.isSaved && (this.isSaved = false);
      },
      deep: true,
    },
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
  created() {
    if (this.empInfo.id) {
      this.orgId = this.empInfo.id;
    }
    this.$http.get(
        `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
    ).then((response) => {
      if (response.status === 200) {
        const {
          data: {data: data},
        } = response;
        data.date_end ? (this.someDaysDuration = true) : '';
        if (Array.isArray(data.participants_age))
          data.participants_age = data.participants_age.reduce(
              (acc, item) => {
                acc.push(item.id);
                return acc;
              },
              [],
          );
        if (Array.isArray(data.target_audience))
          data.target_audience = data.target_audience.reduce((acc, item) => {
            acc.push(item.id);
            return acc;
          }, []);
        this.event = Object.assign(this.event, data, {type: data.type.id});
        if (this.event.date_start) {
          this.event.date_start = new Date(this.event.date_start).getTime();
        }
        if (this.event.date_end) {
          this.event.date_end = new Date(this.event.date_end).getTime();
        }
        if (this.event.registration_available_till) {
          this.event.registration_available_till = new Date(this.event.registration_available_till).getTime();
        }
        if (data.cover) {
          this.previewCover = [...data.cover];
        }
        if (data.images) {
          this.preview = [...data.images];
        }
        if (this.event.speakers.length) {
          this.event.speakers.forEach((s) => {
            if (s.avatar) this.speakersPreview.push([s.avatar]);
          });
        }
        this.signal = true;
        if (this.$route.params.isSaved) {
          this.$nextTick(() => (this.isSaved = true));
        }
      } else throw Error('error occured while journal event getting');
    });
    this.addSpeaker();
  },
  methods: {
    editEvent() {
      if (this.event.cover) delete this.event.cover;
      this.$http.put(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
          Object.assign(this.event,
              {
                date_start: this.event.date_start ? new Date(+this.event.date_start) : null,
                date_end: this.event.date_end ? new Date(+this.event.date_end) : null,
                registration_available_till: this.event.registration_available_till ?
                    new Date(+this.event.registration_available_till) :
                    null,
                images: this.images,
                cover: this.cover,
              }),
      ).then((response) => {
        if (response.status === 200) {
          this.isSaved = true;
          this.isDisabled = false;
          this.created();
          // setTimeout(() => {
          //   this.isDisabled = false;
          //   this.isSaved = false;
          // }, 3000);
        } else throw Error('error occured while journal event editing');
      });
    },
    addSpeaker() {
      let speaker = {...this.ISpeaker};
      this.event.speakers.push(speaker);
    },
    removeSpeaker(idx) {
      this.event.speakers.splice(idx, 1);
    },
    deleteEvent() {
      this.$http.delete(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.$route.params.id}`,
      ).then((response) => {
        if (response.status === 204) {
          this.isSaved = true;
          this.$router.push({
            name: 'ProfileEmpJournalListView',
          });
        } else throw Error('error occured while journal event deleting');
      });
    },
  },
};
</script>

<style lang="scss" scoped>
// wysiwyg
/deep/ .editr {
  b {
    font-weight: bold;
  }

  i {
    font-style: italic;
  }

  ol {
    list-style: decimal;
  }

  ul {
    list-style: disc;
  }
}

// commom util styles
.mb-16 {
  margin-bottom: 16px;
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
</style>
