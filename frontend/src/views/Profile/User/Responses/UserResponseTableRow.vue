<template>
  <tr class="resp-tab__row">
    <td>
      <div class="resp-tab__main">
        <div class="resp-tab__main--type">
          {{ response.vacancy.name }}
        </div>

        <div class="resp-tab__main--info">
          <span style="    white-space: break-spaces;">{{ response.vacancy.salary_min }} &#160;</span>
          &#160;<span> • {{ response.vacancy.employer.name }}</span>
        </div>
      </div>
    </td>

    <td>
      <div class="resp-tab__main">
        <div class="resp-tab__main--type" :class="statusColorClass">
          {{ status }}
        </div>

        <div class="resp-tab__main--info">
          <span>{{ response.created_at | date }}</span>
        </div>
      </div>

    </td>

    <td @click.stop="">
      <UserResponseInviteInfo :response="response" v-if="response.status==='invited'" class="action"/>
      <UserResponseRejectionInfo :response="response" v-if="response.status==='rejected'" class="action"/>
      <UserResponseDelete @responseDeleted="$emit('responseDeleted')" :response="response" class="action"/>
    </td>
  </tr>
</template>
<script>
import UserResponseDelete from '@/views/Profile/User/Responses/UserResponseDelete';
import UserResponseInviteInfo from '@/views/Profile/User/Responses/UserResponseInviteInfo';
import UserResponseRejectionInfo from '@/views/Profile/User/Responses/UserResponseRejectionInfo';

export default {
  name: 'UserResponseTableRow',
  components: {UserResponseRejectionInfo, UserResponseInviteInfo, UserResponseDelete},
  props: {
    response: Object,
  },
  computed: {
    status() {
      switch (this.response.status) {
        case 'send':
          return 'Отправлено';
        case 'seen':
          return 'Просмотрено';
        case 'invited':
          return 'Приглашение';
        case 'rejected':
          return 'Получен отказ';
        default:
          return '';
      }
    },
    statusColorClass() {
      switch (this.response.status) {
        case 'invited':
          return 'green';
        case 'rejected':
          return 'red';
        default:
          return '';
      }
    },
  },
};
</script>
<style scoped>
.red {
  color: #E14761;
}

.green {
  color: #57A003;
}

.action {
  display: inline-block;
  margin-right: 32px;
}
</style>