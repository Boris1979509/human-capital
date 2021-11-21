<template>
  <ProfileOrgWrapper>
    <h4 class="title">
      Уведомления
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <Checkbox id="ege"
                    label="Оповещать по имейлу о новых сообщениях"
                    :checked="fields.is_email_notifications"
                    :margin="24"
                    @change="fields.is_email_notifications = $event"
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
                  viewport="0 0 16 16" />
            Выйти из профиля
          </Button>
        </div>
      </div>
    </section>
  </ProfileOrgWrapper>
</template>

<script>
export default {
  name: 'ProfileOrgSettingsView',

  computed: {
    orgInfo: function() {
      return this.$organization;
    },
    setting_inst: function() {
      return this.$dictionaries.setting_inst;
    },
  },

  created() {
    if (this.orgInfo.length !== 0) {
      this.org_id = this.orgInfo[0].id;

      this.fields = {...this.orgInfo[0].settings};
    }
  },

  data: function() {
    return {
      org_id: '',

      fields: {
        is_email_notifications: false,
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

      if (this.org_id.length !== 0) {
        this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.org_id}/settings`, this.fields)
            .then((response) => {
              if (response.status === 201) this.isSaved = true;
              this.isLoading = false;
            })
            .catch(() => {
              this.isError = true;
              this.submitDisabled = false;
              this.isLoading = false;
            });
      }
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
