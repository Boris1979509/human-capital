<template>
  <div v-if="$loginStatus && userCanFavorite">
    <Button @click.native="addToCalendar" v-if="!isAdded" class="btn--svg" style="margin-bottom: 18px;">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M5 8.01184C4.44772 8.01184 4 8.46041 4 9.01375V10.127C4 10.6803 4.44772 11.1289 5 11.1289H6.88889C7.44117 11.1289 7.88889 10.6803 7.88889 10.127V9.01375C7.88889 8.46041 7.44117 8.01184 6.88889 8.01184H5Z"
            fill="#214EB0"/>
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M5.88867 1.00582C5.88867 0.452476 5.44096 0.00390625 4.88867 0.00390625C4.33639 0.00390625 3.88867 0.452476 3.88867 1.00582V1.33253L2.55556 1.33258C1.14416 1.33258 0 2.47871 0 3.8928V12.9945C0 14.6545 1.34315 16.0002 3 16.0002H13C14.6569 16.0002 16 14.6545 16 12.9945V4.33809C16 2.67807 14.6569 1.33237 13 1.33237H12.1113V1.00582C12.1113 0.452476 11.6636 0.00390625 11.1113 0.00390625C10.559 0.00390625 10.1113 0.452476 10.1113 1.00582V1.33232L5.88867 1.33246V1.00582ZM11.1113 3.33627L11.093 3.3361L4.9045 3.33631L4.88867 3.33644L4.87291 3.33631L2.55469 3.33639L2.54287 3.33633C2.24191 3.34308 2 3.58964 2 3.8928V5.00611H11.9612C12.5135 5.00611 12.9612 5.45468 12.9612 6.00802C12.9612 6.56136 12.5135 7.00993 11.9612 7.00993H2V12.9945C2 13.5479 2.44772 13.9964 3 13.9964H13C13.5523 13.9964 14 13.5479 14 12.9945V4.33809C14 3.78475 13.5523 3.33618 13 3.33618H11.1242L11.1113 3.33627Z"
              fill="#214EB0"/>
      </svg>
      Отметить на календаре
    </Button>

    <Button @click.native="removeFromCalendar" v-else class="btn--svg" style="margin-bottom: 18px;">
      <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
        <path
            d="M5 8.01184C4.44772 8.01184 4 8.46041 4 9.01375V10.127C4 10.6803 4.44772 11.1289 5 11.1289H6.88889C7.44117 11.1289 7.88889 10.6803 7.88889 10.127V9.01375C7.88889 8.46041 7.44117 8.01184 6.88889 8.01184H5Z"
            fill="#214EB0"/>
        <path fill-rule="evenodd" clip-rule="evenodd"
              d="M5.88867 1.00582C5.88867 0.452476 5.44096 0.00390625 4.88867 0.00390625C4.33639 0.00390625 3.88867 0.452476 3.88867 1.00582V1.33253L2.55556 1.33258C1.14416 1.33258 0 2.47871 0 3.8928V12.9945C0 14.6545 1.34315 16.0002 3 16.0002H13C14.6569 16.0002 16 14.6545 16 12.9945V4.33809C16 2.67807 14.6569 1.33237 13 1.33237H12.1113V1.00582C12.1113 0.452476 11.6636 0.00390625 11.1113 0.00390625C10.559 0.00390625 10.1113 0.452476 10.1113 1.00582V1.33232L5.88867 1.33246V1.00582ZM11.1113 3.33627L11.093 3.3361L4.9045 3.33631L4.88867 3.33644L4.87291 3.33631L2.55469 3.33639L2.54287 3.33633C2.24191 3.34308 2 3.58964 2 3.8928V5.00611H11.9612C12.5135 5.00611 12.9612 5.45468 12.9612 6.00802C12.9612 6.56136 12.5135 7.00993 11.9612 7.00993H2V12.9945C2 13.5479 2.44772 13.9964 3 13.9964H13C13.5523 13.9964 14 13.5479 14 12.9945V4.33809C14 3.78475 13.5523 3.33618 13 3.33618H11.1242L11.1113 3.33627Z"
              fill="#214EB0"/>
      </svg>
      Убрать из календаря
    </Button>
  </div>
</template>

<script>
export default {
  name: 'AddToCalendar',

  props: {
    initialState: Boolean,
    type: String,
    itemId: Number,
  },

  computed: {
    userCanFavorite() {
      return this.$user?.type === 1;
    },
  },

  data: function() {
    return {
      isAdded: this.initialState,
    }
  },

  methods: {
    getRoute() {
      switch (this.type) {
        case 'event':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal/${this.itemId}/calendarEntry`;
        case 'curriculum':
          return `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/curriculum/${this.itemId}/calendarEntry`;
      }
    },

    removeFromCalendar() {
      this.$http.delete(this.getRoute()).then(() => {
        this.isAdded = false;
      });
    },

    addToCalendar() {
      this.$http.post(this.getRoute()).then(() => {
        this.isAdded = true;
      });
    },
  },
}
</script>
