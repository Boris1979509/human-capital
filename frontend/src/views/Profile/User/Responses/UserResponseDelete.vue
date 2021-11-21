<template>
  <Button class="btn--delete"
          @click.native="deleteResponse"
          style="height: auto; padding: 0;">
    <Icon xlink="delete" viewport="0 0 16 16"/>
  </Button>
</template>
<script>
export default {
  name: 'UserResponseDelete',
  props: {
    response: Object,
  },
  methods: {
    deleteResponse() {
      this.$http.delete(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/user/responses/${this.response.id}`).
          then(() => {
            this.$emit('responseDeleted', this.response);
          });

    },
  },
};
</script>