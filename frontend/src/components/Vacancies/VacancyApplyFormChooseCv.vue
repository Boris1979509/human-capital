<template>
  <div>
    <Radio id="generated"
           name="userType"
           v-model="chosenCV"
           checked
           :value="{
             type: TYPE_GENERATED,
           }"
           label="Отправить резюме, сформированное на платформе"/>
    <Radio v-for="cvFile in uploadedCVs" :id="cvFile.id" :key="cvFile.id"
           name="userType"
           v-model="chosenCV"
           :value="{
             type: TYPE_UPLOADED,
             file_id: cvFile.id
           }"
           :label="cvFile.file_name"/>
  </div>
</template>
<script>
export default {
  name: 'VacancyApplyFormChooseCV',
  data() {
    return {
      TYPE_GENERATED: 1,
      TYPE_UPLOADED: 2,
      chosenCV: {},
      uploadedCVs: [],
    };
  },
  methods: {
    getUploadedCvs() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/cvs`).then(res => {
        this.uploadedCVs = res.data.data;
      });
    },
  },
  mounted() {
    this.getUploadedCvs();
  },
  watch: {
    chosenCV: {
      deep: true,
      handler: function(val) {
        this.$emit('input', val);
      },
    },
  },
};
</script>
<style scoped lang="scss"></style>