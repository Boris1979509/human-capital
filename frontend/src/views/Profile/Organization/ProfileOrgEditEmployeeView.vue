<template>
  <div>
    <h2 class="title">
      Редактировать данные сотрудника
    </h2>

    <section class="profile__section">
      <div class="grid grid-col-2">
        <TextInput
            class="invert"
            :class="$v.last_name.$invalid ? 'error' : ''"
            :margin="0"
            type="text"
            placeholder="Фамилия"
            v-model="last_name"
            :isLabel="false"
            :required="true"
        />
        <TextInput
            class="invert"
            :class="$v.first_name.$invalid ? 'error' : ''"
            :margin="0"
            type="text"
            placeholder="Имя"
            v-model="first_name"
            :isLabel="false"
            :required="true"
        />
        <TextInput
            class="invert"
            :class="$v.position.$invalid ? 'error' : ''"
            :margin="0"
            type="text"
            placeholder="Должность"
            v-model="position"
            :isLabel="false"
            :required="true"
        />
      </div>
    </section>
    <section class="profile__section">
      <UploadWithCrop :photos.sync="files" :preview="preview"/>
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
          @click.native="editEmployee"
          :disabled="$v.$invalid"
          class="btn--blue mb-16"
      >
        {{ isSaved ? 'Сохранено' : 'Сохранить' }}
      </Button>
      <Button
          @click.native="$refs.modalName.openModal()"
          class="btn--red"
      >
        Удалить
      </Button>
    </section>
    <modal ref="modalName">
      <template v-slot:header></template>

      <template v-slot:body>
        <p class="modal_body_text">Удалить сотрудника?</p>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <div class="btn btn--red" @click="deleteEmployee">
            Удалить
          </div>
          <div class="btn btn--blue" @click="$refs.modalName.closeModal()">
            Отмена
          </div>
        </div>
      </template>
    </modal>
  </div>
</template>

<script>
import {required} from 'vuelidate/lib/validators';

export default {
  name: 'ProfileOrgEditEmployeeView',
  computed: {
    orgInfo: function() {
      return this.$organization;
    },
  },
  watch: {
    $organization() {
      this.getEmployee();
    },
  },
  mounted() {
    this.getEmployee();
  },
  data: function() {
    return {
      first_name: '',
      last_name: '',
      position: '',
      files: [],
      preview: [],
      approved: false,
      isSaved: false,
    };
  },
  validations: {
    first_name: {required},
    last_name: {required},
    position: {required},
  },
  methods: {
    getEmployee() {
      this.orgId = this.orgInfo[0]?.id;
      if (!this.orgId) {
        return;
      }
      this.$http.get(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgId}/employees/${this.$route.params.id}`,
      ).then(({data: {data: data}}) => {
        data.first_name ? (this.first_name = data.first_name) : '';
        data.last_name ? (this.last_name = data.last_name) : '';
        data.position ? (this.position = data.position) : '';
        data.approved ? (this.approved = data.approved) : '';
        data.avatar ? this.preview.push(data.avatar) : '';
      });
    },
    editEmployee: function() {
      this.$http.put(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgId}/employees/${this.$route.params.id}`,
          {
            first_name: this.first_name,
            last_name: this.last_name,
            position: this.position,
            approved: this.approved,
            avatar: this.files,
          },
      ).then(async (response) => {
        if (response.status === 201) this.isSaved = true;
        else throw Error('error occured while employee editing');

        setTimeout(() => {
          this.isSaved = false;
        }, 3000);
        await this.$store.dispatch('GET_ORG_DATA_FROM_SERVER');
        this.$router.push({
          name: 'ProfileOrgEmployeesListView',
        });
      }).catch((err) => {
        console.error('error during work files uploading', err);
      });
    },
    deleteEmployee() {
      this.$http.delete(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgId}/employees/${this.$route.params.id}`,
      ).then(async (response) => {
        if (response.status === 200) {
          await this.$store.dispatch('GET_ORG_DATA_FROM_SERVER');
          this.$router.push({
            name: 'ProfileOrgEmployeesListView',
          });
        } else throw Error('error occured while employee deleting');
      });
    },
  },
};
</script>
<style scoped lang="scss">
.profile__section {
  margin-bottom: 32px;
}

.mb-16 {
  margin-bottom: 16px;
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

.btn-wrapper {
  display: grid;
  grid-template-columns: repeat(2, 1fr);
  grid-gap: 50px;
}

/deep/ .checkbox-container__text {
  padding-left: 12px;
}
</style>
