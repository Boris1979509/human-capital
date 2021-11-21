<template>
  <div>
    <LinkWithIcon class="red" @click="$refs.rejectionForm.openModal()">
      <template slot="icon">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0)">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M0.292893 0.292893C0.683417 -0.0976311 1.31658 -0.0976311 1.70711 0.292893L8 6.58579L14.2929 0.292893C14.6834 -0.0976311 15.3166 -0.0976311 15.7071 0.292893C16.0976 0.683417 16.0976 1.31658 15.7071 1.70711L9.41421 8L15.7071 14.2929C16.0976 14.6834 16.0976 15.3166 15.7071 15.7071C15.3166 16.0976 14.6834 16.0976 14.2929 15.7071L8 9.41421L1.70711 15.7071C1.31658 16.0976 0.683417 16.0976 0.292893 15.7071C-0.0976311 15.3166 -0.0976311 14.6834 0.292893 14.2929L6.58579 8L0.292893 1.70711C-0.0976311 1.31658 -0.0976311 0.683417 0.292893 0.292893Z"
                  fill="#E14761"/>
          </g>
          <defs>
            <clipPath id="clip0">
              <rect width="16" height="16" fill="white"/>
            </clipPath>
          </defs>
        </svg>
      </template>
      <template slot="text">Отказать</template>
    </LinkWithIcon>

    <modal ref="rejectionForm" :is-default-close="false" bodyWidth="800">
      <template v-slot:body>
        <h2 class="modal__title title">
          Приглашение на собеседование
        </h2>
        <h3 class="subheader">
          {{ vacancy.name }}
        </h3>
        <div class="row">
          <TextInput :textarea="true"
                     class="invert col-100"
                     placeholder="Сообщение соискателю"
                     v-model="rejection.message"
                     :isLabel="false"
                     :required="true"/>
        </div>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <Button class="btn btn--red" @click.native="sendRejection">
            Отказать
          </Button>
        </div>
      </template>
    </modal>
  </div>
</template>
<script>
import LinkWithIcon from '@/components/LinkWithIcon';

export default {
  name: 'RejectApplicant',
  components: {LinkWithIcon},
  props: {
    response: Object,
    vacancy: Object,
  },
  data() {
    return {
      rejection: {
        message: '',
      },
    };
  },
  methods: {
    sendRejection() {
      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/responses/${this.response.id}/reject`,
          this.rejection).
          then(() => {
            this.$refs.rejectionForm.closeModal();
            this.$emit('response-status-changed');
          });
    },
  },
};
</script>
<style scoped lang="scss">
.title {
  margin-bottom: 16px;
}

.subheader {
  margin-bottom: 32px;
}
</style>