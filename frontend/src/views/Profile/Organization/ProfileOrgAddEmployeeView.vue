<template>
  <div>
    <h2 class="title">
      Новый сотрудник
    </h2>

    <section class="profile__section">
      <div class="grid grid-col-2">
        <TextInput
          class="invert"
          :class="$v.employee.last_name.$invalid ? 'error' : ''"
          :margin="0"
          type="text"
          placeholder="Фамилия"
          v-model="employee.last_name"
          :isLabel="false"
          :required="true"
        />
        <TextInput
          class="invert"
          :class="$v.employee.first_name.$invalid ? 'error' : ''"
          :margin="0"
          type="text"
          placeholder="Имя"
          v-model="employee.first_name"
          :isLabel="false"
          :required="true"
        />
        <TextInput
          class="invert"
          :class="$v.employee.position.$invalid ? 'error' : ''"
          :margin="0"
          type="text"
          placeholder="Должность"
          v-model="employee.position"
          :isLabel="false"
          :required="true"
        />
      </div>
    </section>
    <section class="profile__section">
      <UploadWithCrop :photos.sync="files" />
    </section>
    <section class="profile__section">
      <Checkbox
        :id="'approved'"
        :checked="approved"
        :label="'Публиковать на сайте'"
        @change="approved = $event"
      />
    </section>
    <section class="profile__section">
      <Button
        @click.native="addEmployee"
        :is-success="isSaved"
        :disabled="$v.$invalid"
        class="btn--blue"
      >
        {{ isSaved ? 'Сохранено' : 'Сохранить' }}
      </Button>
    </section>
    <formAutoSaver
      :observable-fields.sync="employee"
      :save-func="addEmployee"
      :is-saved="isSaved"
    />
  </div>
</template>

<script>
import { required } from 'vuelidate/lib/validators';
import formAutoSaver from '@/components/formAutoSaver';

export default {
  name: 'ProfileOrgAddEmployeesView',
  components: {
    formAutoSaver,
  },
  computed: {
    orgInfo: function() {
      return this.$organization;
    },
  },
  created() {
    if (this.orgInfo.length !== 0) {
      this.orgId = this.orgInfo[0].id;
    }
  },
  data: function() {
    return {
      employee: {
        first_name: '',
        last_name: '',
        position: '',
      },
      files: [],
      approved: false,
      isSaved: false,
      isDisabled: false,
    };
  },

  validations: {
    employee: {
      first_name: { required },
      last_name: { required },
      position: { required },
    },
  },

  methods: {
    addEmployee: function() {
      this.$http
        .post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgId}/employees/`,
          {
            ...this.employee,
            approved: this.approved,
            avatar: this.files,
          }
        )
        .then(async (response) => {
          if (response.status === 201) this.isSaved = true;
          else throw Error('error occured while employee creation');

          // setTimeout(() => {
          //   this.isSaved = false;
          // }, 3000);
          await this.$store.dispatch('GET_ORG_DATA_FROM_SERVER');
          this.employee.first_name = '';
          this.employee.last_name = '';
          this.employee.position = '';
          this.approved = false;
          this.$router.push({
            name: 'ProfileOrgEmployeesListView',
          });
        })
        .catch((err) => {
          this.submitDisabled = false;
          this.isDisabled = false;
          console.log(err);
        });
    },
  },
};
</script>
<style scoped lang="scss">
.profile__section {
  margin-bottom: 32px;
}

.title {
  margin-bottom: 32px;
}

.grid {
  display: grid;
  grid-gap: 24px 32px;
}

.grid-col-2 {
  grid-template-columns: repeat(2, 1fr);
}

/deep/ .checkbox-container__text {
  padding-left: 12px;
}
</style>
