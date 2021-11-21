<template>
  <div>
    <!-- LOGIN STEP 1 -->
    <LoginStep :title="auth.step_1.title" :description="auth.step_1.description" :selected="isStep === 1">
      <template v-slot:inputs>
        <TextInput class="invert password"
                   :class="isError ? 'error' : ''"
                   type="password"
                   placeholder="Пароль"
                   v-model="password"
                   :isLabel="false"
                   :required="true"
                   @keyup.enter.native="isDisabled ? null : logMeIn" />
      </template>

      <template v-slot:submit>
        <Button @click.native="logMeIn" :disabled="isDisabled" :type="'submit'" class="btn--blue">
          Войти
        </Button>

<!--        <div class="login-app__restore">-->
<!--          <router-link to="/login/reset" class="link-svg" title="Восстановление пароля">-->
<!--            <Icon xlink="pass_restore"-->
<!--                  viewport="0 0 16 16" />-->
<!--            Восстановление пароля-->
<!--          </router-link>-->
<!--        </div>-->
      </template>
    </LoginStep>

    <!-- LOGIN STEP SUCCESS -->
    <LoginStep :title="auth.step_2.title" :description="auth.step_2.description" :selected="isStep === 2">
      <template v-slot:inputs>
        <div class="login-app__success">
          <Icon xlink="success"
                viewport="0 0 32 32" />
        </div>
      </template>

      <template v-slot:submit>

      </template>
    </LoginStep>
  </div>
</template>

<script>
export default {
  name: "LoginBlock",

  props: {
    email: {
      type: String,
    }
  },

  data: function() {
    return {
      auth: {
        step_1: {
          title: 'Здравствуйте!',
          description: 'Введите свой пароль, чтобы войти',
        },
        step_2: {
          title: 'Вы успешно вошли',
          description: 'Рады снова вас видеть',
        }
      },

      isStep: 1,

      password: null,

      isError: false,
      isDisabled: false,
    }
  },

  methods: {
    logMeIn: function() {
      if (!this.isDisabled) {
        this.isDisabled = true;

        this.$store.dispatch('LOG_ME_IN', {
          email: this.email,
          password: this.password,
        })
            .then((resolve) => {
              this.isStep = 2;

              if (resolve.status === 200 && resolve.data.access_token.length > 0) {
                this.$store.commit('CHANGE_LOADING', true);
                this.$router.push({ name: 'MainView' });
              }
            })
            .catch(() => {
              this.isDisabled = false;
              this.isError = true;
            });
      }
    },
  },
}
</script>
