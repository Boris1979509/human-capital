<template>
  <div class="inner-page curriculum" v-if="loaded">
    <Breadcrumbs ref="breadcrumbs" :margin="32"/>
    <div class="avatar" v-if="organizationAvatar">
      <img :src="organizationAvatar">
    </div>
    <CurriculumMainInfo :curriculum="curriculum"/>
    <CurriculumAdmissions :curriculum="curriculum"/>
    <CurriculumLearningOptions :options="curriculum.learning_options"/>
    <CurriculumSimilar :curriculum="curriculum"/>
  </div>
</template>

<script>
import CurriculumMainInfo from '../components/Curriculum/CurriculumMainInfo';
import CurriculumAdmissions from '../components/Curriculum/CurriculumAdmissions';
import CurriculumLearningOptions from '../components/Curriculum/CurriculumLearningOptions';
import CurriculumSimilar from '../components/Curriculum/CurriculumSimilar';

export default {
  name: 'CurriculumMainView',
  components: {CurriculumMainInfo, CurriculumAdmissions, CurriculumLearningOptions, CurriculumSimilar},
  data() {
    return {
      loaded: false,
      curriculum: {},
    };
  },
  computed: {
    organizationAvatar() {
      return this.curriculum.institution.avatar?.thumb;
    },
  },
  watch: {
    '$route'() {
      this.getCurriculum();
    },
  },
  mounted() {
    this.getCurriculum();
  },
  methods: {
    updateBreadcrumbs() {
      this.$route.meta.breadcrumb = this.$route.meta.breadcrumb.filter(o => o.type !== 'inst');
      this.$route.meta.breadcrumb.push({
        type: 'inst',
        name: this.curriculum.institution.full_name,
        link: 'InstitutionView',
        params: {
          id: this.curriculum.institution.id,
        },
      },
      {
        type: 'inst',
        name: this.curriculum.name,
      });
    },
    getCurriculum() {
      this.loaded = false;
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/curricula/${this.$route.params.id}`).
          then(({data}) => {
            this.curriculum = data.data;
            this.updateBreadcrumbs();
          }).
          finally(() => {
            this.loaded = true;
          });
    },
  },
};
</script>

<style scoped lang="scss">
.curriculum {
  & .avatar {
    max-height: 56px;

    & img {
      height: 100%;
      width: 100%;
      border-radius: 0;
    }
  }
}
</style>
