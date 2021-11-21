<template>
  <div>
    <Button class=""
            @click.native.prevent.stop="$refs.infoForm.openModal()"
            style="height: auto; padding: 0;">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <g clip-path="url(#clip0)">
          <path
              d="M7 8C7 7.44772 7.44772 7 8 7C8.55228 7 9 7.44772 9 8C9 8.55228 9.44772 9 10 9C10.5523 9 11 8.55228 11 8C11 6.34315 9.65685 5 8 5C6.34315 5 5 6.34315 5 8C5 9.65685 6.34315 11 8 11C8.55228 11 9 10.5523 9 10C9 9.44772 8.55228 9 8 9C7.44772 9 7 8.55228 7 8Z"
              fill="#3D75E4"/>
          <path fill-rule="evenodd" clip-rule="evenodd"
                d="M8.00004 2C3.19768 2 0.784227 6.00237 0.190901 7.19285C0.0636339 7.44821 0 7.69924 0 8.00001C0 8.30078 0.0636341 8.55181 0.190903 8.80717C0.78423 9.99765 3.19768 14 8.00004 14C12.8024 14 15.2158 9.99765 15.8091 8.80717C15.9364 8.55181 16 8.27591 16 8.00001C16 7.72411 15.9364 7.44821 15.8091 7.19285C15.2158 6.00237 12.8024 2 8.00004 2ZM8.00004 12C4.53414 12 2.59929 9.16544 2 8.00001C2.59929 6.83458 4.53414 4 8.00004 4C11.4659 4 13.4007 6.83458 14 8.00001C13.4007 9.16544 11.4659 12 8.00004 12Z"
                fill="#3D75E4"/>
        </g>
        <defs>
          <clipPath id="clip0">
            <rect width="16" height="16" fill="white"/>
          </clipPath>
        </defs>
      </svg>

    </Button>
    <modal ref="infoForm" :is-default-close="true" bodyWidth="800">
      <template v-slot:body>
        <h2 class="modal__title title">
          Отказ по вакансии
        </h2>
        <router-link class="vacancy_link" :to="`/job/vacancies/${response.vacancy.id}`">
          {{ response.vacancy.name }}
        </router-link>
        <div class="row">
          <div class="col-100">
            <div class="message">
              {{ response.rejection.message }}
            </div>
          </div>
        </div>
      </template>
    </modal>
  </div>
</template>
<script>
export default {
  name: 'UserResponseRejectionInfo',
  props: {
    response: Object,
    vacancy: Object,
  },
  methods: {},
};
</script>
<style scoped>
.vacancy_link {
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  color: #3D75E4;
  margin-top: 16px;
  margin-bottom: 32px;
  display: block;
}
</style>