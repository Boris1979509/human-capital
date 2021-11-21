<template>
  <ProfileOrgWrapper>
    <div v-if="isComponentLoaded">
      <UniversityMainInfoForm v-if="orgInfo[0].inst_type_id == 18 || orgInfo[0].inst_type_id == 19"/>

      <KindergartenMainInfoForm v-if="orgInfo[0].inst_type_id == 16 || orgInfo[0].inst_type_id == 20"/>

      <HighSchoolMainInfoForm v-if="orgInfo[0].inst_type_id == 17"/>

      <DigitalEduMainInfoForm v-if="orgInfo[0].inst_type_id == 21"/>
    </div>

    <Loading v-else/>
  </ProfileOrgWrapper>
</template>

<script>
import KindergartenMainInfoForm from '@/components/OrgProfile/KindergartenMainInfoForm';
import UniversityMainInfoForm from '@/components/OrgProfile/UniversityMainInfoForm';
import HighSchoolMainInfoForm from '@/components/OrgProfile/HighSchoolMainInfoForm';
import DigitalEduMainInfoForm from '@/components/OrgProfile/DigitalEduMainInfoForm';

export default {
  name: 'ProfileOrgInfoView',

  components: {
    DigitalEduMainInfoForm,
    HighSchoolMainInfoForm,
    UniversityMainInfoForm,
    KindergartenMainInfoForm,
  },

  computed: {
    orgInfo: function() {
      return this.$organization;
    },
  },

  mounted() {
    this.isComponentLoaded = false;

    if (this.orgInfo.length === 0) {
      this.$store.dispatch('GET_ORG_DATA_FROM_SERVER').then(() => {
        this.isComponentLoaded = true;
      });
    } else {
      this.isComponentLoaded = true;
    }
  },

  data: function() {
    return {
      isComponentLoaded: false,
    };
  },
};
</script>
