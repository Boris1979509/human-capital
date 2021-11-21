<template>
  <LoginAuthWrapper>
    <LoginStep @onTurnBack="stepBack"
               :title="restore.step_1.title"
               :description="restore.step_1.description"
               :selected="isStep === 1"
               :is-back="true">
      <template v-slot:inputs>
        <TextInput class="invert email"
                   :class="isError ? 'error' : ''"
                   placeholder="E-mail"
                   v-model="email"
                   :isLabel="false"
                   :required="true"/>
      </template>

      <template v-slot:submit>
        <Button :disabled="isDisabled || isEmailFilled" @click.native="checkEmail(2)" class="btn--blue">
          Продолжить
        </Button>
      </template>
    </LoginStep>

    <LoginStep @onTurnBack="stepBack"
               :title="restore.step_2.title"
               :sub-title="email"
               :description="restore.step_2.description"
               :selected="isStep === 2"
               :is-back="true">
      <template v-slot:inputs>

      </template>

      <template v-slot:submit>

      </template>
    </LoginStep>
  </LoginAuthWrapper>
</template>

<script>
export default {
  name: "PasswordResetView",

  computed: {
    isEmailFilled() {
      return !this.email;
    },
  },

  data: function() {
    return {
      restore: {
        step_1: {
          title: 'Восстановление пароля',
          description: 'Введите имейл с которым вы регистрировались'
        },
        step_2: {
          title: 'Восстановление пароля',
          description: 'Мы отправили вам инструкцию по восстановлению пароля на имейл'
        }
      },

      isStep: 1,

      email: null,

      isDisabled: false,

      isError: false,
    }
  },

  methods: {
    setStep: function(key) {
      this.isStep = key;
    },

    checkEmail: function(key) {
      this.setStep(key);
    },

    stepBack: function() {
      if (this.isStep === 1) {
        this.$router.push('/login');
      } else {
        this.setStep(this.isStep - 1);
      }
    }
  }
}
</script>
