<template>
  <ProfileUserWrapper>
    <section class="profile__section">
      <table class="resp-tab">
        <thead>
        <tr>
          <th>Вакансия</th>
          <th>Статус</th>
          <th></th>
        </tr>
        </thead>

        <tbody>
        <UserResponseTableRow
            v-for="response in responses"
            :response="response"
            :key="response.id"
            @responseDeleted="getResponses"
            @click.native="openResponseDetailed(response)"
        />

        </tbody>
      </table>
    </section>

    <modal ref="responseDetailed" :is-default-close="true">
      <template v-slot:body>
        <UserResponseDetailedInfo :response="detailedResponse"/>
      </template>
    </modal>
  </ProfileUserWrapper>
</template>

<script>

import UserResponseTableRow from '@/views/Profile/User/Responses/UserResponseTableRow';
import UserResponseDetailedInfo from '@/views/Profile/User/Responses/UserResponseDetailedInfo';

export default {
  name: 'ProfileUserResponsesView',

  components: {UserResponseDetailedInfo, UserResponseTableRow},

  computed: {},

  mounted() {
    this.getResponses();
  },

  data: function() {
    return {
      responses: [],
      detailedResponse: {},
    };
  },

  methods: {
    getResponses() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/responses`).then(res => {
        this.responses = res.data.data;
      });
    },
    openResponseDetailed(response) {
      this.detailedResponse = response;
      this.$refs.responseDetailed.openModal();
    },

  },
};
</script>
<style scoped lang="scss"></style>
