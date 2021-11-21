<template>
  <div class="employee">
    <div class="employee__avatar">
      <img :src="avatar ? avatar.url : require('@/assets/svg/user.svg')" alt="" />
    </div>
    <div class="employee__name">
      {{ firstName }}<br />
      {{ lastName }}
    </div>

    <div class="employee__position">
      {{ position }}
    </div>
  </div>
</template>

<script>
export default {
  name: 'EmployeeCard',

  props: {
    avatar: {
      type: Object,
    },
    firstName: {
      type: String,
    },
    lastName: {
      type: String,
    },
    position: {
      type: String,
    },
  },
}
</script>
