<template>
  <div class="profile__container">
    <h4 class="title" v-if="event">{{ event.title }}</h4>
    <Loading v-if="isLoading"/>
    <template v-else-if="registrations.length > 0">

      <table class="resp-tab">
        <thead>
        <tr>
          <th>
            <Checkbox
                :id="'regcheck'"
                @change="selectAll($event)"
            />
          </th>
          <th>ФИО</th>
          <th>Имейл</th>
          <th>Телефон</th>
          <th v-for="(question,i) in questions" :key="i">{{ question }}</th>
        </tr>
        </thead>

        <tbody>
        <template v-for="(registration, i) in registrations">
          <td :key="'reg'+i+'check'">
            <Checkbox :id="'reg'+i+'check'"
                      @change="addOrRemove(selectedRegistrations, registration.id)"
                      :checked="selectedRegistrations.includes(registration.id)"
            />
          </td>
          <td :key="'reg'+i+'name'">{{ registration.fields.name }}</td>
          <td :key="'reg'+i+'email'">{{ registration.fields.email }}</td>
          <td :key="'reg'+i+'phone'">{{ registration.fields.phone }}</td>
          <td style="min-width: 200px" :key="'reg'+i+question" v-for="question in registration.questions">{{ question }}
          </td>

        </template>
        </tbody>
      </table>
      <div class="row">
        <div class="col-33">
          <Button class="invert btn--blue" @click.native="acceptRegistrations">Одобрить</Button>
        </div>
        <div class="col-33">
          <Button class="invert btn--red" @click.native="rejectRegistrations">Отклонить</Button>
        </div>
        <div class="col-33">
          <Button class="" @click.native="deleteRegistrations">Удалить</Button>
        </div>
      </div>
    </template>
    <NothingFound v-else text="Не найдено"/>


  </div>
</template>

<script>

export default {
  name: 'EventsRegistrationsList',
  components: {},
  props: {
    id: [Number, String],
  },
  data: function() {
    return {
      isLoading: false,
      registrations: [],
      event: null,
      selectedRegistrations: [],
    };
  },

  computed: {
    questions() {
      if (this.registrations.length < 1) {
        return [];
      }
      return Object.keys(this.registrations[0].questions);
    },
  },

  mounted() {
    this.getRegistrations();
  },

  methods: {
    getRegistrations() {
      console.log(111);
      this.isLoading = true;
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/event/${this.id}/registrations`).then(res => {
        this.registrations = res.data.registrations;
        this.event = res.data.event;
      }).finally(() => {
        this.isLoading = false;
      });
    },
    addOrRemove(array, value) {
      let index = array.indexOf(value);

      if (index === -1) {
        array.push(value);
      } else {
        array.splice(index, 1);
      }
    },
    selectAll(isSelect) {
      if (isSelect) {
        this.selectedRegistrations = this.registrations.map(reg => reg.id);
      } else {
        this.selectedRegistrations = [];
      }
    },
    acceptRegistrations() {
      this.isLoading = true;
      this.$http.post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/event-registrations/accept`,
          {
            ids: this.selectedRegistrations,
          },
      ).then(() => {
        this.selectedRegistrations = [];
      }).finally(() => {
        this.isLoading = false;
      });
    },
    rejectRegistrations() {
      this.isLoading = true;
      this.$http.post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/event-registrations/reject`,
          {
            ids: this.selectedRegistrations,
          },
      ).then(() => {
        this.selectedRegistrations = [];
      }).finally(() => {
        this.isLoading = false;
      });
    },
    deleteRegistrations() {
      this.isLoading = true;
      this.$http.delete(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/event-registrations/`,
          {
           params:{
             ids: this.selectedRegistrations,
           }
          },
      ).then(() => {
        this.selectedRegistrations = [];
        this.getRegistrations();
      }).finally(() => {
        this.isLoading = false;
      });
    },
  },
};
</script>

<style scoped lang="scss">
.resp-tab {
  max-width: 100%;
  overflow-x: scroll;
  display: block;
  margin-bottom: 16px;
}

.resp-tab th, .resp-tab td {
  padding: 4px 10px;
  border: none;
}

.resp-tab th {
  font-weight: 500;
  font-size: 13px;
  line-height: 16px;
  color: var(--main-color-dark-trans-light);
  text-align: left;
  vertical-align: top;
}

.resp-tab td {
  vertical-align: middle;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: var(--main-color-dark);
  padding: 16px;
}
</style>