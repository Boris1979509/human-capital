<template>
  <div class="cv">
    <div class="generated" v-if="response.cv.type===1">
      <div class="col-100 covering_letter" v-if="response.covering_letter">
        {{ response.covering_letter }}
      </div>
      <GeneratedCV :cv="response.cv.data" class="col-100" @seen="markSeen"/>
    </div>

    <div class="uploaded" v-if="response.cv.type===2">
      <UploadedCV :response="response" class="download-cv" @seen="markSeen"/>
      <div class="col-100 covering_letter" v-if="response.covering_letter">
        {{ response.covering_letter }}
      </div>
    </div>
  </div>
</template>

<script>
import UploadedCV from '@/components/EmployerProfile/UploadedCV';
import GeneratedCV from '@/components/EmployerProfile/GeneratedCV';

export default {
  name: 'VacancyResponseCv',
  props: {
    response: Object,
  },
  components: {GeneratedCV, UploadedCV},
  methods: {
    markSeen() {
      if (this.response.status === 'send')
        this.$http.post(
            `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/responses/${this.response.id}/seen`,
        );
    },
  },
};
</script>

<style scoped>
.generated .covering_letter {
  margin-bottom: 24px;
  margin-top: 24px;
}

.covering_letter {
  margin-top: 24px;
  font-style: normal;
  font-weight: normal;
  font-size: 13px;
  line-height: 16px;
  color: #04153E;
  opacity: 0.72;
}

.download-cv {
  margin-left: 60px;
}
</style>