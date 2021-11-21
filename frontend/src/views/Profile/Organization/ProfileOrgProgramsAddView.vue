<template>
  <ProfileOrgWrapper>
    <div v-if="isComponentLoaded">
      <AddProgramUniversityForm v-if="orgInfo[0].inst_type_id == 18 || orgInfo[0].inst_type_id == 17 || orgInfo[0].inst_type_id == 19" />

      <AddProgramKindergartenForm v-if="orgInfo[0].inst_type_id == 16 || orgInfo[0].inst_type_id == 20" />

      <AddProgramHighSchoolForm v-if="orgInfo[0].inst_type_id == 1" />

      <AddProgramDigitalEduForm v-if="orgInfo[0].inst_type_id == 21" />
    </div>

    <Loading v-else />
  </ProfileOrgWrapper>
</template>

<script>
import AddProgramKindergartenForm from '@/components/OrgProfile/AddProgramKindergartenForm';
import AddProgramDigitalEduForm from '@/components/OrgProfile/AddProgramDigitalEduForm';
import AddProgramHighSchoolForm from '@/components/OrgProfile/AddProgramHighSchoolForm';
import AddProgramUniversityForm from "@/components/OrgProfile/AddProgramUniversityForm";

export default {
  name: 'ProfileOrgProgramsAddView',

  components: {
    AddProgramUniversityForm,
    AddProgramHighSchoolForm,
    AddProgramDigitalEduForm,
    AddProgramKindergartenForm
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
    }
  },
}
</script>
