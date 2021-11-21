<template>
  <div>
    <Button class="btn--blue" v-if="userCanApply" @click.native="$refs.applyForm.openModal()">Откликнуться</Button>
    <Button class="btn--blue" v-else disabled>Вы уже откликнулись</Button>
    <modal ref="applyForm" :is-default-close="false">
      <template v-slot:body>
        <h2 class="modal__title title">
          Отклик на вакансию
        </h2>
        <h3 class="subheader">
          {{ vacancy.name }}
        </h3>
        <div class="cv_title">
          Резюме для отклика
        </div>
        <VacancyApplyFormChooseCV v-model="cv"/>
        <TextInput :textarea="true"
                   class="invert letter"
                   placeholder="Сопроводительное письмо (необязательно)"
                   v-model="coveringLetter"
                   :isLabel="false"
                   :required="true"/>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <Button class="btn btn--blue" @click.native="apply">
            Откликнуться
          </Button>
        </div>
      </template>
    </modal>
    <modal ref="success" :is-default-close="false">
      <template v-slot:body>
        <svg width="56" height="56" viewBox="0 0 56 56" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
                d="M55.5553 8.19227C56.1411 8.77806 56.1411 9.7278 55.5553 10.3136L18.0606 47.8082C17.4748 48.394 16.525 48.394 15.9393 47.8082L0.487193 32.3562C-0.0985943 31.7704 -0.0985958 30.8207 0.48719 30.2349C1.07298 29.6491 2.02272 29.6491 2.60851 30.2349L16.9999 44.6263L53.434 8.19227C54.0197 7.60648 54.9695 7.60648 55.5553 8.19227Z"
                fill="#57A003"/>
        </svg>
        <h2 class="modal__title title">
          Спасибо <br>за отклик
        </h2>
        Он будет отправлен работодателю
        <Button class="btn btn--light" @click.native="$refs.success.closeModal()" style="margin-top: 32px;">Понятно
        </Button>
      </template>
    </modal>
  </div>
</template>
<script>
import VacancyApplyFormChooseCV from '@/components/Vacancies/VacancyApplyFormChooseCv';

export default {
  name: 'VacancyApplyForm',
  components: {VacancyApplyFormChooseCV},
  props: {
    vacancy: Object,
  },
  data() {
    return {
      cv: {
        type: 1,
      },
      coveringLetter: '',
      userApplied: false,
    };
  },
  computed: {
    userCanApply() {
      return !this.vacancy?.is_user_applied && !this.userApplied;
    },
  },
  methods: {
    apply() {
      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/vacancies/${this.vacancy.id}/responses`, {
        covering_letter: this.coveringLetter,
        cv_type: this.cv.type,
        cv_file_id: this.cv.file_id || null,
      }).then(() => {
        this.$refs.applyForm.closeModal();
        this.userApplied = true;
        this.$refs.success.openModal();
      });
    },
  },
};
</script>
<style scoped lang="scss">
.subheader {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.48;
}

.cv_title {
  font-weight: 800;
  font-size: 20px;
  line-height: 24px;
  color: #04153E;
  margin-top: 32px;
  margin-bottom: 24px;
}

.letter {
  margin-top: 32px;
}
</style>