<template>
  <LoginAuthWrapper v-if="!isLoading">
    <!-- INITIAL STEP -->
    <LoginStep :title="steps.initial.title"
               :description="steps.initial.description"
               :selected="!isRegistration && !isLogin">
      <template v-slot:inputs>
        <TextInput class="invert email"
                   :class="isError ? 'error' : ''"
                   placeholder="E-mail"
                   v-model="email"
                   :isLabel="false"
                   :required="true"
                   @keyup.enter.native="checkEmail" />
      </template>

      <template v-slot:submit>
        <Button :disabled="isDisabled || isEmailFilled" @click.native="checkEmail" class="btn--blue">
          Продолжить
        </Button>
      </template>
    </LoginStep>

    <!-- REGISTRATION BLOCK -->
    <RegistrationBlock :email="email" v-if="isRegistration" @toInitialScreen="setInitial" />

    <!-- LOGIN BLOCK -->
    <LoginBlock :email="email" v-if="isLogin"/>
  </LoginAuthWrapper>

  <Loading v-else />
</template>

<script>
export default {
  name: 'LoginAuthView',

  data: function() {
    return {
      steps: {
        initial: {
          title: 'Войдите или зарегистрируйтесь',
          description: 'Если вы не зарегистрированы, учётная запись будет создана автоматически',
        },
      },

      isRegistration: false,
      isLogin: false,

      isDisabled: false,

      email: null,

      isError: false,
    }
  },

  computed: {
    isEmailFilled() {
      return !this.email;
    },
    isLoading() {
      return this.$store.getters.GET_LOGIN_LOADING;
    }
  },

  methods: {
    checkEmail: function() {
      const check = /[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*@(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?/;

      if (check.test(this.email)) {
        this.isError = false;
        this.sendEmail()
      } else {
        this.isError = true;
      }
    },

    sendEmail: function() {
      if (!this.isDisabled) {
        this.isDisabled = true;

        this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/check?email=${this.email}`)
            .then((response) => {
              if (response.status === 200) {
                this.isError = false;
                this.isLogin = true;
              }
            })
            .catch((error) => {
              if (error.response.status === 404) {
                this.isError = false;
                this.isRegistration = true;
              }

              this.isDisabled = false;
            });
      }
    },

    setInitial: function() {
      this.isRegistration = false;
      this.isLogin = false;
      this.isDisabled = false;
    },
  }
}
</script>
