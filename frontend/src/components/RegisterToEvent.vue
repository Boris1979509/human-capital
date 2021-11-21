<template>
  <div v-if="$loginStatus && userCanRegister">
    <div class="container" v-if="isRegistered">
      <Button class="btn--blue" disabled>
        Заявка подана
      </Button>
      <div class="status" :class="getStatusClass">
        {{ getStatus }}
      </div>
    </div>

    <div class="container" v-else>

      <Button class="btn--blue" @click.native="$refs.registerForm.openModal()">
        Подать заявку
      </Button>


    </div>
    <modal ref="registerForm" bodyWidth="800">
      <template v-slot:body>
        <h2 class="modal__title title">
          Заявка на участие
        </h2>
        <h3 class="subheader">
          {{ event.title }}, {{ event.date_start | dateTime }}
        </h3>
        <div class="row">
          <div class="col-100">
            <TextInput
                class="invert"
                placeholder="Фио"
                v-model="registration.fields.name"
            />
          </div>
        </div>
        <div class="row">
          <div class="col-50">
            <TextInput
                class="invert"
                placeholder="Телефон"
                v-model="registration.fields.phone"
            />
          </div>
          <div class="col-50">
            <TextInput
                v-model="registration.fields.email"
                placeholder="Имейл"
                class="invert"
            />
          </div>
        </div>
        <div class="row" v-for=" (question, i) in registration.questions " :key="i">
          <div class="col-100">
            <TextInput
                :textarea="true"
                :placeholder="question.question"
                class="invert"
                v-model="question.answer"
            />
          </div>
        </div>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <Button class="btn btn--blue" @click.native="registerToEvent">
            Подать заявку
          </Button>
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
export default {
  name: 'RegisterToEvent',
  props: {
    isRegisteredInitial: Boolean,
    event: Object,
  },
  data() {
    return {
      isRegistered: false,
      registrationStatus: null,
      registration: {
        fields: {
          name: '',
          phone: '',
          email: '',
        },
        questions: [],

      },
    };
  },
  computed: {
    userCanRegister() {
      return this.$user?.type === 1;
    },
    getStatus() {
      switch (this.registrationStatus) {
        case 1:
          return 'На рассмотрении';
        case 2:
          return 'Одобрена';
        case 3:
          return 'Отклонена';
        default:
          return '';
      }
    },
    getStatusClass() {
      switch (this.registrationStatus) {
        case 1:
          return 'pending';
        case 2:
          return 'accepted';
        case 3:
          return 'declined';
        default:
          return '';
      }
    },
  },
  mounted() {
    this.getRegistration();

    for (let question of this.event.registration_questions) {
      this.registration.questions.push({question: question, answer: ''});
    }

    this.registration.fields.name = this.$user?.last_name + ' ' + this.$user?.first_name;
    this.registration.fields.phone = this.$user?.phone;
    this.registration.fields.email = this.$user?.email;
  },

  methods: {
    getRegistration() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/event/${this.event.id}/registrations/user`).
          then(res => {
            this.isRegistered = true;
            this.registrationStatus = res.data.data.status;
          });
    },
    registerToEvent() {
      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/event/${this.event.id}/registrations`,
          {
            fields: this.registration.fields,
            questions: this.registration.questions.reduce((questions, question) => {
              questions[question.question] = question.answer;
              return questions;
            }, {}),
          }).then(() => {
        this.isRegistered = true;
        this.$refs.registerForm.closeModal();
      }).catch(err => {
        if (err.response.status === 403) {
          alert(err.response.data);
        } else {
          alert('Заполните все поля');
        }
        console.log(err.response.data);
      });
    },
  },
};
</script>

<style scoped>
.container {
  display: flex;
  margin-bottom: 18px;
  flex-direction: column;
}

.icon {
  margin-right: 8px;
}

.text {
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  color: #214EB0;
}

.subheader {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.48;
  margin-bottom: 16px;
  margin-top: 8px;
}

.status {
  text-align: center;
  font-size: 14px;
  margin-top: 8px;
}

.pending {
  color: #D06E0B;
}

.accepted {
  color: #57A003
}
</style>