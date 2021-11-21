<template>
  <ProfileEmployerWrapper>
    <h6 class="title" style="margin-bottom: 12px;">
      Уведомления по электронной почте
    </h6>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <Checkbox id="new_responses"
                    label="Уведомлять меня об откликах на вакансию"
                    :checked="fields.notify_responses"
                    :margin="0"
                    @change="fields.notify_responses = $event"
          />
        </div>

        <div class="col-100">
          <Checkbox id="new_orders"
                    label="Уведомлять меня о новых заявках от соискателей"
                    :checked="fields.notify_from_applicants"
                    :margin="0"
                    @change="fields.notify_from_applicants = $event"
          />
        </div>

        <div class="col-100">
          <Checkbox id="new_messages"
                    label="Уведомлять меня о новых сообщениях от пользователей"
                    :checked="fields.notify_messages"
                    :margin="0"
                    @change="fields.notify_messages = $event"
          />
        </div>

        <div class="col-100">
          <Checkbox id="event_reg"
                    label="Уведомлять меня о регистрации на мое мероприятие "
                    :checked="fields.notify_event_registration"
                    :margin="20"
                    @change="fields.notify_event_registration = $event"
          />
        </div>
      </div>

      <div class="row">
        <div class="col-50">
          <Select :array="setting_inst"
                  :class="isError ? 'error' : ''"
                  placeholder="Частота оповещений"
                  :pre-selected="fields.notify_frequency_id"
                  @select="fields.notify_frequency_id = $event"/>
        </div>
      </div>
    </section>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <Button @click.native="checkForm" :is-success="isSaved" :is-spinner="isLoading" :disabled="submitDisabled"
                  class="btn--blue">
            {{ isSaved ? 'Сохранено' : 'Сохранить' }}
          </Button>
        </div>
      </div>
    </section>

    <section class="profile__section profile__section--logout">
      <div class="row">
        <div class="col-100">
          <Button @click.native="logOut" class="link-svg">
            <Icon xlink="logout"
                  viewport="0 0 16 16"/>
            Выйти из профиля
          </Button>
        </div>
      </div>
    </section>
  </ProfileEmployerWrapper>
</template>

<script>
export default {
  name: 'ProfileEmployerSettingsView',

  computed: {
    employerInfo: function() {
      return this.$employer;
    },
    setting_inst: function() {
      return this.$dictionaries.setting_inst;
    },
  },

  created() {
    if (this.employerInfo.length !== 0) {
      this.fields = {...this.employerInfo.employer_settings};
    }
  },

  data: function() {
    return {
      fields: {
        notify_responses: false,
        notify_from_applicants: false,
        notify_messages: false,
        notify_event_registration: false,

        notify_frequency_id: null,
      },

      isError: false,
      isSaved: false,
      isLoading: false,
      submitDisabled: false,
    }
  },

  methods: {
    checkForm: function() {
      if (!this.isError) {
        this.sendData();
      }
    },

    sendData: function() {
      this.submitDisabled = true;
      this.isSaved = false;
      this.isLoading = true;
      this.isError = false;

      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/employer/settings`, this.fields)
          .then((response) => {
            if (response.status === 201) this.isSaved = true;
            this.isLoading = false;
          })
          .catch(() => {
            this.isError = true;
            this.submitDisabled = false;
            this.isLoading = false;
          });

    },

    logOut: function() {
      this.$store.dispatch('LOG_ME_OUT')
          .then(() => {
            this.$router.push('/login');
          })
          .catch(err => console.log(err));
    }
  },
}
</script>
