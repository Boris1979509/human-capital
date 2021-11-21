<template>
  <div>
    <!-- REGISTRATION STEP 1 -->
    <LoginStep @onTurnBack="stepBack" :title="register.step_1.title" :sub-title="email"
               :description="register.step_1.description" :selected="isStep === 1" :is-back="true">
      <template v-slot:inputs>
        <router-link to="/agreement" target="_blank" title="Пользовательское соглашение" class="link-svg">
          <Icon xlink="small_file"
                viewport="0 0 16 16"
          />
          Пользовательское соглашение
        </router-link>
      </template>

      <template v-slot:submit>
        <Button @click.native="setStep(2)" class="btn--blue">
          Прочитано, соглашаюсь
        </Button>
      </template>
    </LoginStep>

    <!-- REGISTRATION STEP 2 -->
    <LoginStep @onTurnBack="stepBack" :title="register.step_2.title" :sub-title="email"
               :description="register.step_2.description" :selected="isStep === 2" :is-back="true">
      <template v-slot:inputs>
        <Radio id="1"
               name="userType"
               v-model="fields.type"
               value="1"
               label="Физическое лицо"/>

        <Radio id="2"
               name="userType"
               v-model="fields.type"
               value="2"
               label="Учебное заведение"/>

        <Radio id="3"
               name="userType"
               v-model="fields.type"
               value="3"
               label="Работодатель"/>
      </template>

      <template v-slot:submit>
        <Button @click.native="setStep(3)" :disabled="!fields.type" class="btn--blue">
          Продолжить
        </Button>
      </template>
    </LoginStep>

    <!-- REGISTRATION STEP 3 -->
    <LoginStep @onTurnBack="stepBack" :title="register.step_3.title" :sub-title="email"
               :description="register.step_3.description" :selected="isStep === 3" :is-back="true">
      <template v-slot:inputs>
        <div v-if="fields.type == '1'">
          <TextInput class="invert"
                     :class="errors.first_name ? 'error' : ''"
                     type="text"
                     placeholder="Ваше имя"
                     v-model="fields.first_name"
                     :isLabel="false"
                     :required="true"/>

          <TextInput class="invert"
                     :class="errors.last_name ? 'error' : ''"
                     type="text"
                     placeholder="Ваша фамилия"
                     v-model="fields.last_name"
                     :isLabel="false"
                     :required="true"/>
        </div>

        <div v-if="fields.type == '2'">
          <TextInput class="invert"
                     :class="errors.full_name ? 'error' : ''"
                     type="text"
                     placeholder="Полное наименование"
                     v-model="fields.full_name"
                     :isLabel="false"
                     :required="true"/>

          <TextInput class="invert"
                     :class="errors.short_name ? 'error' : ''"
                     type="text"
                     placeholder="Краткое наименование"
                     v-model="fields.short_name"
                     :isLabel="false"
                     :required="true"/>

          <Select :array="inst_type"
                  :class="errors.inst_type_id ? 'error' : ''"
                  placeholder="Тип учебного заведения"
                  :pre-selected="fields.inst_type_id"
                  @select="fields.inst_type_id = $event"/>

          <Select :array="cities"
                  :class="errors.city_id ? 'error' : ''"
                  placeholder="Город"
                  :pre-selected="fields.city_id"
                  @select="fields.city_id = $event"/>
        </div>

        <div v-if="fields.type == '3'">
          <TextInput class="invert"
                     :class="errors.name ? 'error' : ''"
                     type="text"
                     placeholder="Полное наименование организации"
                     v-model="fields.name"
                     :isLabel="false"
                     :required="true"/>

          <Select :array="type_branch"
                  :class="errors.branch_id ? 'error' : ''"
                  placeholder="Отрасль"
                  :pre-selected="fields.branch_id"
                  @select="fields.branch_id = $event"/>
        </div>

        <TextInput class="invert password"
                   :class="errors.password ? 'error' : ''"
                   type="password"
                   placeholder="Пароль"
                   v-model="fields.password"
                   :isLabel="false"
                   :required="true"/>

        <TextInput class="invert password"
                   :class="errors.password ? 'error' : ''"
                   type="password"
                   placeholder="Пароль повторно"
                   v-model="fields.password_repeat"
                   :isLabel="false"
                   :required="true"/>

        <div v-if="errors">
          <div v-for="(error, index) in errors" :key="index" class="errors">
            {{ error }}
          </div>
        </div>
      </template>

      <template v-slot:submit>
        <Button @click.native="checkForm" class="btn--blue">
          Продолжить
        </Button>
      </template>
    </LoginStep>

    <!-- REGISTRATION STEP 4 -->
    <LoginStep :title="register.step_4.title" :sub-title="email"
               :description="register.step_4.description" :selected="isStep === 4">
      <template v-slot:inputs>

      </template>

      <template v-slot:submit>
        <Button @click.native="$emit('toInitialScreen')" class="btn--light">
          Войти
        </Button>
      </template>
    </LoginStep>
  </div>
</template>

<script>
export default {
  name: 'RegistrationBlock',
  props: {
    email: {
      type: String,
    },
    active: {
      type: Boolean,
    },
  },

  computed: {
    inst_type: function() {
      return this.$dictionaries.inst_type;
    },
    cities() {
      return this.$cities.data;
    },
    type_branch: function() {
      return this.$dictionaries.type_branch;
    },
  },

  data: function() {
    return {
      register: {
        step_1: {
          title: 'Новый пользователь',
          description: 'Для продолжения регистрации, необходимо согласие с правилами сервиса',
        },
        step_2: {
          title: 'Новый пользователь',
          description: 'Я хочу зарегистрироваться как',
        },
        step_3: {
          title: 'Придумайте и введите пароль',
          description: '',
        },
        step_4: {
          title: 'Спасибо за регистрацию!',
          description: 'Теперь вы можете войти с помощью своего логина и пароля.',
        },
      },

      isStep: 1,

      errors: {},

      fields: {
        full_name: '',
        short_name: '',
        inst_type_id: null,
        city_id: null,

        name: '',
        branch_id: null,

        first_name: '',
        last_name: '',
        password: '',
        password_repeat: '',
        type: '',
      },

      empty: {
        full_name: '',
        short_name: '',
        inst_type_id: null,
        city_id: null,

        name: '',
        branch_id: null,

        first_name: '',
        last_name: '',
        password: '',
        password_repeat: '',
        type: '',
      },
    };
  },

  watch: {
    password_repeat: 'checkPasswordsEquality',
    password: 'checkPasswordsEquality',
  },

  methods: {
    setStep: function(key) {
      this.isStep = key;
    },

    submitRegister: function() {
      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/auth/signup`, {
        email: this.email,

        first_name: this.fields.first_name,
        last_name: this.fields.last_name,

        name: this.fields.name,
        branch_id: this.fields.branch_id,

        full_name: this.fields.full_name,
        short_name: this.fields.short_name,
        inst_type_id: this.fields.inst_type_id,
        city_id: this.fields.city_id,

        password: this.fields.password,
        password_confirmation: this.fields.password_repeat,
        type: this.fields.type,
      }).then(() => {
        this.setStep(4);
      }).catch(error => {
        console.log(error, 'error');
      });
    },

    stepBack: function() {
      if (this.isStep === 1) {
        this.$emit('toInitialScreen');
      } else {
        this.setStep(this.isStep - 1);
      }

      this.errors = {};
      this.fields = {...this.empty};
    },

    checkForm: function() {
      this.errors = {};
      const {
        password,
        password_repeat,
        first_name,
        last_name,
        type,
        full_name,
        short_name,
        city_id,
        inst_type_id,
        name,
        branch_id,
      } = this.fields;

      if (type == '1') {
        if (first_name.length === 0) this.errors.first_name = 'Укажите ваше имя';
        if (last_name.length === 0) this.errors.last_name = 'Укажите вашу фамилию';
      }
      if (type == '2') {
        if (full_name.length === 0) this.errors.full_name = 'Укажите полное название учебного заведения';
        if (short_name.length === 0) this.errors.short_name = 'Укажите краткое название учебного заведения';
        if (inst_type_id === null) this.errors.inst_type_id = 'Укажите тип учебного зеведения';
        if (city_id === null) this.errors.city_id = 'Укажите город';
      }
      if (type == '3') {
        if (name.length === 0) this.errors.name = 'Укажите полное название организации';
        if (branch_id === null) this.errors.branch_id = 'Укажите отрасль';
      }
      if (password !== password_repeat) this.errors.password = 'Пароли должны совпадать';
      if (password.length < 6) this.errors.password = 'Пароль слишком короткий (не менее 6 символов)';
      if (password.length === 0) this.errors.password = 'Укажите пароль';

      if (Object.keys(this.errors).length === 0 && this.errors.constructor === Object) {
        this.submitRegister();
      }
    },
  },
};
</script>
