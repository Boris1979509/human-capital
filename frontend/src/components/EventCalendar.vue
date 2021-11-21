<template>
  <div v-if="isCalendar">
    <div class="calendar-months-changer">
      <Button @click.native="changeMonthNext()">
        <svg width="6" height="16" viewBox="0 0 6 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g opacity="0.32">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M3.92388 0.819307C4.18933 0.369163 4.77235 0.224208 5.2177 0.497626C5.65199 0.764252 5.79256 1.32967 5.53371 1.76863L2.15861 7.49198C1.97379 7.8054 1.97379 8.19448 2.15861 8.50791L5.53371 14.2313C5.79256 14.6702 5.65199 15.2356 5.2177 15.5023C4.77235 15.7757 4.18933 15.6307 3.92388 15.1806L0.266126 8.97791C-0.0887088 8.3762 -0.0887087 7.62369 0.266126 7.02198L3.92388 0.819307Z"
                  fill="#04153E"/>
          </g>
        </svg>
      </Button>

      <div class="calendar-months-changer__month">
        {{ currentMonth }}
      </div>

      <Button @click.native="changeMonthPrev()">
        <svg width="6" height="16" viewBox="0 0 6 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g opacity="0.32">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M3.92388 0.819307C4.18933 0.369163 4.77235 0.224208 5.2177 0.497626C5.65199 0.764252 5.79256 1.32967 5.53371 1.76863L2.15861 7.49198C1.97379 7.8054 1.97379 8.19448 2.15861 8.50791L5.53371 14.2313C5.79256 14.6702 5.65199 15.2356 5.2177 15.5023C4.77235 15.7757 4.18933 15.6307 3.92388 15.1806L0.266126 8.97791C-0.0887088 8.3762 -0.0887087 7.62369 0.266126 7.02198L3.92388 0.819307Z"
                  fill="#04153E"/>
          </g>
        </svg>
      </Button>
    </div>

    <div class="calendar-filter" v-if="calendarType!=='employer'">
      <Button @click.native="filterEvents('all')"
              :class="currentType === 'all' ? 'calendar-filter__btn--active' : ''"
              class="calendar-filter__btn calendar-filter__btn--all">
        <span>Все</span>
      </Button>

      <Button @click.native="filterEvents('event')"
              :class="currentType === 'event' ? 'calendar-filter__btn--active' : ''"
              class="calendar-filter__btn calendar-filter__btn--events">
        <span>Мероприятия</span>
      </Button>

      <Button @click.native="filterEvents('curriculum')"
              :class="currentType === 'curriculum' ? 'calendar-filter__btn--active' : ''"
              class="calendar-filter__btn calendar-filter__btn--programs"
      >
        <span>Образовательные программы</span>
      </Button>
    </div>

    <div class="calendar">
      <div class="calendar__container">
        <FullCalendar
            ref="fullCalendar"
            :options='calendarOptions'
        >
          <template v-slot:eventContent='arg'>
            {{ arg.event.title }}
          </template>
        </FullCalendar>
      </div>

      <div class="event-view" v-if="currentEvent">
        <router-link :to="currentEvent._def.extendedProps.type === 'event' ?
        `/journal/event/${currentEvent._def.extendedProps.eventId}` : `/programs/${currentEvent._def.extendedProps.eventId}`">
          <div class="event-view__img">
            <div class="event-view__type"
                 :class="currentEvent._def.extendedProps.type === 'event' ? 'event-view__type--event' : 'event-view__type--curriculum'">
              {{ currentEvent._def.extendedProps.type === 'event' ? 'Мероприятие' : 'Образовательная программа' }}
            </div>
            <img :src="require('@/assets/svg/no_foto.svg')" :alt="currentEvent._def.title">
          </div>

          <div class="event-view__title">
            {{ currentEvent._def.title }}
          </div>
        </router-link>

        <div class="event-view__info">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M5 8.01184C4.44772 8.01184 4 8.46041 4 9.01375V10.127C4 10.6803 4.44772 11.1289 5 11.1289H6.88889C7.44117 11.1289 7.88889 10.6803 7.88889 10.127V9.01375C7.88889 8.46041 7.44117 8.01184 6.88889 8.01184H5Z"
                fill="#3D75E4"/>
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M5.88867 1.00582C5.88867 0.452476 5.44096 0.00390625 4.88867 0.00390625C4.33639 0.00390625 3.88867 0.452476 3.88867 1.00582V1.33253L2.55556 1.33258C1.14416 1.33258 0 2.47871 0 3.8928V12.9945C0 14.6545 1.34315 16.0002 3 16.0002H13C14.6569 16.0002 16 14.6545 16 12.9945V4.33809C16 2.67807 14.6569 1.33237 13 1.33237H12.1113V1.00582C12.1113 0.452476 11.6636 0.00390625 11.1113 0.00390625C10.559 0.00390625 10.1113 0.452476 10.1113 1.00582V1.33232L5.88867 1.33246V1.00582ZM11.1113 3.33627L11.093 3.3361L4.9045 3.33631L4.88867 3.33644L4.87291 3.33631L2.55469 3.33639L2.54287 3.33633C2.24191 3.34308 2 3.58964 2 3.8928V5.00611H11.9612C12.5135 5.00611 12.9612 5.45468 12.9612 6.00802C12.9612 6.56136 12.5135 7.00993 11.9612 7.00993H2V12.9945C2 13.5479 2.44772 13.9964 3 13.9964H13C13.5523 13.9964 14 13.5479 14 12.9945V4.33809C14 3.78475 13.5523 3.33618 13 3.33618H11.1242L11.1113 3.33627Z"
                  fill="#3D75E4"/>
          </svg>
          <span>{{ parseTime(currentEvent._instance.range.start) }} - {{
              parseTime(currentEvent._instance.range.end)
            }}</span>
        </div>

        <div class="event-view__info" v-if="currentEvent._def.extendedProps.address">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M14 6C14 9.15381 12.2402 11.8955 10.7637 13.6406C10.4072 14.0615 9.77734 14.1133 9.35352 13.7578C8.93262 13.4004 8.87988 12.7695 9.23633 12.3477C10.4971 10.8584 12 8.54785 12 6C12 3.79443 10.2061 2 8 2C5.79443 2 4 3.79443 4 6C4 10.4492 8.57959 14.1826 8.62549 14.2197C9.05566 14.5654 9.125 15.1943 8.77979 15.625C8.58203 15.8721 8.2915 16 7.99902 16C7.77979 16 7.55957 15.9287 7.37549 15.7812C7.15576 15.6055 2 11.418 2 6C2 2.69141 4.69141 0 8 0C11.3086 0 14 2.69141 14 6ZM8 4.49982C7.17157 4.49982 6.5 5.17139 6.5 5.99982C6.5 6.82825 7.17157 7.49982 8 7.49982C8.82843 7.49982 9.5 6.82825 9.5 5.99982C9.5 5.17139 8.82843 4.49982 8 4.49982Z"
                fill="#3D75E4"/>
          </svg>
          <span>{{ currentEvent._def.extendedProps.address }}</span>
        </div>

        <div class="event-view__info" v-if="currentEvent._def.extendedProps.phone">
          <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
            <path
                d="M5 2C4.44772 2 4 2.44772 4 3V13C4 13.5523 4.44772 14 5 14H11C11.5523 14 12 13.5523 12 13V7C12 6.44772 12.4477 6 13 6C13.5523 6 14 6.44772 14 7V13C14 14.6569 12.6569 16 11 16H5C3.34315 16 2 14.6569 2 13V3C2 1.34315 3.34315 0 5 0H11C12.6569 0 14 1.34315 14 3V4C14 4.55228 13.5523 5 13 5C12.4477 5 12 4.55228 12 4V3C12 2.44772 11.5523 2 11 2H10C10 2.55228 9.55228 3 9 3H7C6.44772 3 6 2.55228 6 2H5Z"
                fill="#3D75E4"/>
            <path
                d="M8 13C8.55228 13 9 12.5523 9 12C9 11.4477 8.55228 11 8 11C7.44772 11 7 11.4477 7 12C7 12.5523 7.44772 13 8 13Z"
                fill="#3D75E4"/>
          </svg>
          <span>{{ currentEvent._def.extendedProps.phone }}</span>
        </div>

        <Button @click.native="editEvent(currentEvent._def.extendedProps.type, currentEvent._def.extendedProps.eventId)"
                class="btn--edit">
          <Icon xlink="edit-small" viewport="0 0 16 16"/>
          Редактировать
        </Button>

        <Button
            @click.native="deleteEvent(currentEvent._def.extendedProps.type, currentEvent._def.extendedProps.eventId)"
            class="btn--delete">
          <Icon xlink="delete" viewport="0 0 16 16"/>
          Удалить из календаря
        </Button>
      </div>
    </div>
  </div>
</template>

<script>
import FullCalendar from '@fullcalendar/vue';
import dayGridPlugin from '@fullcalendar/daygrid';
import timeGridPlugin from '@fullcalendar/timegrid';
import interactionPlugin from '@fullcalendar/interaction';
import ruLocale from '@fullcalendar/core/locales/ru';
import momentPlugin from '@fullcalendar/moment';

export default {
  name: 'EventCalendar',

  components: {
    FullCalendar,
  },

  props: {
    institutionId: {
      type: [String, Number],
    },
    calendarType: {
      type: String,
    }
  },

  created() {
    if (this.calendarType === 'institution') {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.institutionId}/calendar`)
          .then(resolve => {
            this.calendarOptions.events = [...resolve.data.data];
            this.calendarOptions.events.forEach(item => {
              if (item.type === 'event') item.backgroundColor = '#8DC95E';
              if (item.type === 'curriculum') item.backgroundColor = '#A2ABBE';
              item.eventId = item.id;
              item.end = item.stopped_at;
              item.start = item.started_at;
              item.allDay = true;
            })
          }).then(() => {
        this.initialEventsSet = [...this.calendarOptions.events];
        this.isCalendar = true;
      }).then(() => {
        this.currentMonth = this.$refs.fullCalendar.getApi().view.title;
      });
    }
    if (this.calendarType === 'user') {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/calendar`)
          .then(resolve => {
            this.calendarOptions.events = [...resolve.data.data];
            this.calendarOptions.events.forEach(item => {
              if (item.type === 'event') item.backgroundColor = '#8DC95E';
              if (item.type === 'curriculum') item.backgroundColor = '#A2ABBE';
              item.eventId = item.id;
              item.end = item.stopped_at;
              item.start = item.started_at;
              item.allDay = true;
            })
          }).then(() => {
        this.initialEventsSet = [...this.calendarOptions.events];
        this.isCalendar = true;
      }).then(() => {
        this.currentMonth = this.$refs.fullCalendar.getApi().view.title;
      });
    }
    if (this.calendarType === 'employer') {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/${this.$employer.id}/calendar`)
          .then(resolve => {
            this.calendarOptions.events = [...resolve.data.data];
            this.calendarOptions.events.forEach(item => {
              if (item.type === 'event') item.backgroundColor = '#8DC95E';
              if (item.type === 'curriculum') item.backgroundColor = '#A2ABBE';
              item.eventId = item.id;
              item.end = item.stopped_at;
              item.start = item.started_at;
              item.allDay = true;
            })
          }).then(() => {
        this.initialEventsSet = [...this.calendarOptions.events];
        this.isCalendar = true;
      }).then(() => {
        this.currentMonth = this.$refs.fullCalendar.getApi().view.title;
      });
    }
  },

  data: function() {
    return {
      isCalendar: false,
      currentMonth: null,
      currentEvent: null,
      currentType: 'all',
      initialEventsSet: null,
      filteredEvents: null,
      months: ['Январь', 'Февраль', 'Март', 'Апрель', 'Май', 'Июнь', 'Июль', 'Август', 'Сентябрь', 'Октябрь', 'Ноябрь', 'Декабрь'],

      calendarOptions: {
        plugins: [
          dayGridPlugin,
          timeGridPlugin,
          interactionPlugin,
          momentPlugin,
        ],
        headerToolbar: {
          left: '',
          center: '',
          right: ''
        },
        dayCellContent: this.popupCreator,
        titleFormat: 'MMMM',
        locale: ruLocale,
        initialView: 'dayGridMonth',
        events: [],
        eventBorderColor: 'transparent',
        eventTextColor: '#fff',
        editable: false,
        selectable: false,
        selectMirror: true,
        dayMaxEvents: true,
        weekends: true,
        eventClick: this.getEventData,
      },
    }
  },

  updated() {
    if (this.currentEvent) {
      this.resizeCalendar();
    }
  },

  methods: {
    parseTime(time) {
      return this.$moment(time).format('D MMMM hh:mm');
    },

    changeMonthNext() {
      let calendarApi = this.$refs.fullCalendar.getApi();
      calendarApi.prev();
      this.currentMonth = this.months[calendarApi.getDate().getMonth()];
    },

    changeMonthPrev() {
      let calendarApi = this.$refs.fullCalendar.getApi();
      calendarApi.next();

      this.currentMonth = this.months[calendarApi.getDate().getMonth()];
    },

    filterEvents(type) {
      this.currentType = type;
      if (type === 'all') {
        this.calendarOptions.events = this.initialEventsSet;
      } else {
        this.calendarOptions.events = this.initialEventsSet.filter(item => item.type === type);
      }
    },

    getEventData(data) {
      this.currentEvent = data.event;
    },

    editEvent(type, id) {
      if (type === 'event') {
        this.$router.push(`journal/edit-event/${id}`);
      }
      if (type === 'curriculum') {
        this.$router.push(`programs/edit/${this.institutionId}/${id}`);
      }
    },

    deleteEvent(type, id) {
      this.$http.delete(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/${type}/${id}/calendarEntry`)
          .then(() => {
            this.currentEvent = null;
            this.calendarOptions.events = this.initialEventsSet.filter(item => item.eventId !== id);
          });
    },

    resizeCalendar() {
      let calendarApi = this.$refs.fullCalendar.getApi();
      calendarApi.updateSize();
    },

    popupCreator(arg) {
      return {
        html: `${this.calendarType !== 'user' ? `
          <div class="calendar__day">${arg.dayNumberText}</div>

          <button class="btn calendar__popover-btn">
            <svg width="24" height="24" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
              <circle cx="12" cy="12" r="12" fill="#3D75E4"/>
              <path fill-rule="evenodd" clip-rule="evenodd" d="M11 11V6C11 5.44772 11.4477 5 12 5C12.5523 5 13 5.44772 13 6V11H18C18.5523 11 19 11.4477 19 12C19 12.5523 18.5523 13 18 13H13V18C13 18.5523 12.5523 19 12 19C11.4477 19 11 18.5523 11 18V13H6C5.44772 13 5 12.5523 5 12C5 11.4477 5.44772 11 6 11H11Z" fill="white"/>
            </svg>

            <div class="calendar__popover">
              <a href="/profile/org/journal/add-event" class="calendar__popover-link">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M7 7V2C7 1.44772 7.44772 1 8 1C8.55228 1 9 1.44772 9 2V7H14C14.5523 7 15 7.44772 15 8C15 8.55228 14.5523 9 14 9H9V14C9 14.5523 8.55228 15 8 15C7.44772 15 7 14.5523 7 14V9H2C1.44772 9 1 8.55228 1 8C1 7.44772 1.44772 7 2 7H7Z" fill="#3D75E4"/>
                </svg>
                Создать мероприятие
              </a>
              <a href="/profile/org/programs/add" class="calendar__popover-link">
                <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                  <path fill-rule="evenodd" clip-rule="evenodd" d="M7 7V2C7 1.44772 7.44772 1 8 1C8.55228 1 9 1.44772 9 2V7H14C14.5523 7 15 7.44772 15 8C15 8.55228 14.5523 9 14 9H9V14C9 14.5523 8.55228 15 8 15C7.44772 15 7 14.5523 7 14V9H2C1.44772 9 1 8.55228 1 8C1 7.44772 1.44772 7 2 7H7Z" fill="#3D75E4"/>
                </svg>
                Создать образовательную программу
              </a>
            </div>
          </button>` :
            `<div class="calendar__day">${arg.dayNumberText}</div>`}`
      };
    },
  }
}
</script>

<style lang="scss">
.event-view {
  max-width: 300px;

  flex-shrink: 0;

  margin-left: 32px;
  margin-top: 36px;

  &__img {
    position: relative;

    border-radius: 16px;
    overflow: hidden;

    margin-bottom: 16px;

    img {
      display: block;

      height: 183px;
      width: 100%;

      object-fit: cover;
      object-position: center;
    }
  }

  &__type {
    position: absolute;

    top: 8px;
    left: 8px;

    display: flex;
    align-items: center;

    height: 24px;

    font-weight: normal;
    font-size: 13px;
    line-height: 16px;
    color: #fff;

    border-radius: 16px;

    padding: 4px 12px;

    &--event {
      background: #8DC95E;
    }

    &--curriculum {
      background: #A2ABBE;
    }
  }

  &__title {
    font-weight: 800;
    font-size: 24px;
    line-height: 28px;
    color: #04153E;

    margin-bottom: 24px;
  }

  &__info {
    display: flex;
    align-items: center;

    font-weight: 500;
    font-size: 16px;
    line-height: 20px;
    color: #04153E;
    opacity: 0.72;

    margin-bottom: 12px;

    svg {
      flex-shrink: 0;
      margin-right: 6px;
    }
  }

  .btn {
    padding: 0;
  }
}

.calendar-months-changer {
  display: flex;
  align-items: center;
  justify-content: space-between;

  width: 210px;

  margin-bottom: 32px;

  &__month {
    width: 130px;
    font-weight: 800;
    font-size: 24px;
    line-height: 28px;
    color: #04153E;
    text-align: center;

    margin: 0 12px;

    &::first-letter {
      text-transform: capitalize;
    }
  }

  .btn {
    height: 40px;
    width: 40px;

    padding: 0;
  }

  .btn:last-child {
    transform: rotate(180deg);
  }
}

.calendar-filter {
  display: flex;
  align-items: center;

  margin-bottom: 38px;

  &__btn {
    position: relative;

    height: 24px;
    width: auto;

    border-radius: 16px;
    padding: 4px 12px 4px 20px;

    &:not(:last-child) {
      margin-right: 8px;
    }

    span {
      display: block;
      font-weight: 500;
      font-size: 13px;
      line-height: 16px;
      color: #FFFFFF;

      margin-left: 4px;
    }

    &:after {
      content: '';
      display: block;

      position: absolute;
      left: 12px;
      top: calc(50% - 4px);

      width: 8px;
      height: 8px;

      border-radius: 4px;
      opacity: 0.16;
      background: #FFFFFF;
    }

    &--all {
      background: #3D75E4;
    }

    &--events {
      background: #8DC95E;
    }

    &--programs {
      background: #A2ABBE;
    }

    &--active {

      &:after {
        opacity: 1;
      }
    }

    &:hover {
      &:after {
        opacity: 1;
      }
    }
  }
}

.calendar {
  display: flex;
  min-height: 100%;

  &__container {
    flex: 1;
  }

  &__day {
    font-weight: normal;
    font-size: 16px;
    line-height: 20px;
    color: var(--main-color-dark-trans-hard);

    padding: 8px;
  }

  &__popover-btn {
    position: absolute;

    bottom: 8px;
    right: 8px;

    display: none;

    width: 24px;
    height: 24px;

    padding: 0;

    &:hover .calendar__popover {
      display: block;
    }
  }

  &__popover-link {
    display: flex;
    align-items: center;

    font-weight: 500;
    font-size: 13px;
    line-height: 16px;
    color: var(--main-color-dark);

    &:not(:last-child) {
      margin-bottom: 6px;
    }

    svg {
      flex-shrink: 0;

      margin-right: 6px;
    }
  }

  &__popover {
    position: absolute;

    right: 0;
    bottom: 0;

    min-width: 300px;

    display: none;

    box-shadow: 0px 9px 28px rgba(17, 48, 121, 0.18);
    border-radius: 8px;
    background: #FFFFFF;

    padding: 16px;

    z-index: 999;
  }
}

.fc {
  width: 100%;

  font-family: Golos, sans-serif;

  margin: 0 auto;

  .fc-toolbar.fc-header-toolbar {
    margin: 0;
  }

  .fc-daygrid-event {
    height: 20px;
    border-radius: 16px;

    padding: 0 12px 0 26px;

    cursor: pointer;
  }

  .fc-h-event {
    position: relative;

    .fc-event-main {
      height: 18px;

      line-height: 16px;
      font-weight: 500;
      font-size: 10px;

      text-overflow: ellipsis;
      overflow: hidden;
    }

    &:after {
      content: '';
      display: block;

      position: absolute;
      left: 12px;
      top: calc(50% - 4px);

      width: 8px;
      height: 8px;

      border-radius: 4px;
      opacity: 0.16;
      background: #FFFFFF;

      z-index: 999;
    }

    &:hover {
      &:after {
        opacity: 1;
      }
    }
  }

  &.fc-direction-ltr .fc-daygrid-event.fc-event-start {
    margin-left: 8px;
  }

  &.fc-direction-ltr .fc-daygrid-event.fc-event-end {
    margin-right: 8px;
  }

  .fc-daygrid-day.fc-day-today {
    background: #F6F9FE;

    .calendar__day {
      color: #3D75E4;
    }
  }

  .fc-daygrid-day-events {
    margin-top: 34px;
  }

  .fc-daygrid-day-frame {

    &:hover {
      .calendar__popover-btn {
        display: block;
      }
    }
  }

  .fc-scrollgrid-sync-inner {
    display: flex;
    justify-content: flex-end;
  }

  .fc-col-header-cell-cushion {
    font-weight: normal;
    font-size: 16px;
    line-height: 20px;
    text-align: right;
    color: var(--main-color-dark-trans-light);

    padding: 8px;
  }

  &.fc-theme-standard tbody td {
    border-right: 1px solid #C0D6F6;
    border-bottom: 1px solid #C0D6F6;
    border-left: 1px solid #C0D6F6;
    border-top: 1px solid #C0D6F6;
  }

  &.fc-theme-standard .fc-scrollgrid, &.fc-theme-standard thead td, &.fc-theme-standard thead th {
    border: none;
  }
}

.fc-toolbar-chunk {
  display: flex;
  align-items: center;
}
</style>
