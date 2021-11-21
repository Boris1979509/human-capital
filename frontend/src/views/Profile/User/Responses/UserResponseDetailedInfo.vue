<template>
  <div v-if="response.id">
    <h2 class="modal__title title">Отклик на вакансию</h2>
    <router-link class="vacancy_link" :to="`/job/vacancies/${response.vacancy.id}`">
      {{ response.vacancy.name }}
    </router-link>
    <div class="resume_link" @click="openCv">
      <div class="resume_link-icon">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
                d="M4.5 2.5C3.94772 2.5 3.5 2.94772 3.5 3.5V12.5C3.5 13.0523 3.94772 13.5 4.5 13.5H11.5C12.0523 13.5 12.5 13.0523 12.5 12.5V9.5C12.5 9.38791 12.4648 9.3034 12.3203 9.20705C12.1461 9.09094 11.8517 9 11.5 9H11C9.34315 9 8 7.65685 8 6V4.5C8 3.94772 8.44772 3.5 9 3.5C9.55229 3.5 10 3.94772 10 4.5V6C10 6.55228 10.4477 7 11 7H11.5C12.1483 7 12.8539 7.15906 13.4297 7.54295C14.0352 7.9466 14.5 8.61209 14.5 9.5V12.5C14.5 14.1569 13.1569 15.5 11.5 15.5H4.5C2.84315 15.5 1.5 14.1569 1.5 12.5V3.5C1.5 1.84315 2.84315 0.5 4.5 0.5H9.58013C10.4559 0.5 11.288 0.882688 11.8579 1.54763C11.8752 1.56782 11.8917 1.5887 11.9074 1.61021L14.3087 4.9118C14.6336 5.35843 14.5348 5.98386 14.0882 6.30871C13.6416 6.63357 13.0161 6.53484 12.6913 6.0882L10.3174 2.82445C10.1284 2.61809 9.861 2.5 9.58013 2.5H4.5Z"
                fill="#3D75E4"/>
        </svg>
      </div>
      <div class="resume_link-text"> Резюме</div>
    </div>
    <div class="covering_letter">
      {{ response.covering_letter }}
    </div>
  </div>
</template>
<script>
export default {
  name: 'UserResponseDetailedInfo',
  props: {
    response: Object,
  },
  data() {
    return {
      CV_TYPE_GENERATED: 1,
      CV_TYPE_UPLOADED: 2,
    };
  },
  computed: {},
  methods: {
    openCv() {
      if (this.response.cv_type === this.CV_TYPE_GENERATED) {
        this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/resume`, {
          responseType: 'blob',
        }).then((res) => {
          const url = window.URL.createObjectURL(
              new Blob([res.data], {type: 'application/pdf'}),
          );
          const link = document.createElement('a');
          link.href = url;
          link.setAttribute('target', '_blank');
          link.click();
        });
      } else {
        const link = document.createElement('a');
        link.href = this.response.cv_file.url;
        link.setAttribute('target', '_blank');
        link.click();

      }
    },
  },
};
</script>
<style scoped lang="scss">

.vacancy_link {
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  color: #3D75E4;
  margin-top: 16px;
  margin-bottom: 32px;
  display: block;
}

.resume_link {
  display: flex;
  margin-bottom: 24px;
  cursor: pointer;
}

.resume_link-icon {
  margin-right: 8px;
}

.resume_link-text {
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  color: #3D75E4;
}
</style>