<template>
  <div v-if="$loginStatus">
    <div class="container">
      <div class="icon">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M5.88835e-05 3C5.88835e-05 1.34315 1.3432 0 3.00006 0H13.0001C14.6569 0 16.0001 1.34315 16.0001 3V10C16.0001 11.6569 14.6569 13 13.0001 13H5.33339L1.60006 15.8C1.15823 16.1314 0.53143 16.0418 0.200059 15.6C-0.131312 15.1582 -0.0417689 14.5314 0.400059 14.2L4.40006 11.2C4.57315 11.0702 4.78369 11 5.00006 11H13.0001C13.5523 11 14.0001 10.5523 14.0001 10V3C14.0001 2.44772 13.5523 2 13.0001 2H3.00006C2.44777 2 2.00006 2.44772 2.00006 3V11C2.00006 11.5523 1.55234 12 1.00006 12C0.447774 12 5.88835e-05 11.5523 5.88835e-05 11V3Z"
              fill="#214EB0"/>
          <path
              d="M4 5C4 4.44772 4.44772 4 5 4H11C11.5523 4 12 4.44772 12 5C12 5.55228 11.5523 6 11 6H5C4.44772 6 4 5.55228 4 5Z"
              fill="#214EB0"/>
          <path
              d="M5 7C4.44772 7 4 7.44772 4 8C4 8.55229 4.44772 9 5 9H9C9.55229 9 10 8.55229 10 8C10 7.44772 9.55229 7 9 7H5Z"
              fill="#214EB0"/>
        </svg>
      </div>
      <div class="text">
        Написать работодателю
      </div>
    </div>


  </div>
</template>

<script>
export default {
  name: 'ContactEmployer',
  props: {
    employer: Object,
  },
  data() {
    return {};
  },
  computed: {},

  methods: {},
};
</script>

<style scoped>
.container {
  display: flex;
  margin-bottom: 18px;
  cursor: pointer;
  margin-top: 26px;
}

.icon {
  margin-right: 8px;
}

.text {
  font-style: normal;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  color: #214EB0;
}
</style>