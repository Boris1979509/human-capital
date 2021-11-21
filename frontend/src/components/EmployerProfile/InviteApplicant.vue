<template>
  <div>
    <LinkWithIcon class="blue" @click="$refs.inviteForm.openModal()">
      <template slot="icon">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <g clip-path="url(#clip0)">
            <path fill-rule="evenodd" clip-rule="evenodd"
                  d="M5.4012 2.4796C4.5988 2.45522 3.79216 2.74765 3.18111 3.35688C1.61431 4.91897 1.61431 7.45163 3.1811 9.01373L7.25919 13.0796C7.83906 13.6577 8.48519 13.625 8.89028 13.2212C8.92032 13.1912 8.95179 13.1636 8.98448 13.1382C8.68117 12.9961 8.39676 12.8001 8.14545 12.5496L7.08161 11.4889C6.68991 11.0984 6.68991 10.4652 7.08161 10.0747C7.4733 9.68418 8.10837 9.68418 8.50007 10.0747L9.56392 11.1354C9.94675 11.5171 10.5955 11.5211 10.9961 11.1217C11.0037 11.1144 11.0117 11.1077 11.0194 11.1007C10.8811 11.0095 10.7499 10.9031 10.6281 10.7817L9.20963 9.36744C8.81793 8.97692 8.81793 8.34375 9.20963 7.95323C9.60132 7.56271 10.2364 7.56271 10.6281 7.95323L12.0466 9.36744C12.2424 9.56271 12.5599 9.56271 12.7558 9.36744C12.9516 9.17218 12.9516 8.8556 12.7558 8.66034L10.238 6.15013C9.26444 6.79318 7.93981 6.68711 7.08203 5.83189C6.10278 4.85558 6.10278 3.27267 7.08203 2.29636L7.61395 1.76603C8.58216 0.800726 9.83726 0.452368 10.9988 0.479485C12.1474 0.5063 13.282 0.898748 14.1162 1.52057C15.0315 2.20292 15.6902 3.45105 15.9141 4.71212C16.1407 5.98866 15.9591 7.50828 14.8834 8.65868C14.9911 9.41102 14.7547 10.2029 14.1743 10.7817C13.7444 11.2103 13.1972 11.4507 12.6358 11.503C12.7633 11.8562 12.6852 12.2666 12.4013 12.5496C12.3902 12.5607 12.3788 12.5715 12.3673 12.582C11.8347 13.0905 11.1542 13.3707 10.4607 13.4152C10.6913 13.7993 10.6407 14.3044 10.3087 14.6354C8.95119 15.9889 7.02349 15.673 5.84072 14.4938L1.76264 10.4279C-0.587547 8.0848 -0.587547 4.28581 1.76264 1.94266C2.78093 0.927429 4.13 0.440055 5.46229 0.480527C6.01598 0.497347 6.45116 0.95849 6.43429 1.51052C6.41742 2.06255 5.95489 2.49642 5.4012 2.4796ZM10.9518 2.47894C10.2143 2.46172 9.53308 2.68108 9.03241 3.18024L8.50049 3.71057C8.30464 3.90584 8.30464 4.22242 8.50049 4.41768C8.69634 4.61294 9.01387 4.61294 9.20972 4.41768L9.55671 4.07173L9.56424 4.06414L9.57185 4.05663L10.2736 3.35702C10.6653 2.9665 11.3003 2.9665 11.692 3.35702C12.0837 3.74754 12.0837 4.38071 11.692 4.77123L13.7457 6.81883C13.9945 6.3205 14.0538 5.70839 13.9388 5.06066C13.7786 4.15847 13.3193 3.42374 12.9149 3.12233C12.4294 2.7604 11.7023 2.49646 10.9518 2.47894Z"
                  fill="#214EB0"/>
          </g>
          <defs>
            <clipPath id="clip0">
              <rect width="16" height="16" fill="white"/>
            </clipPath>
          </defs>
        </svg>
      </template>
      <template slot="text">Пригласить на собеседование</template>
    </LinkWithIcon>

    <modal ref="inviteForm" :is-default-close="false" bodyWidth="800">
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
                     v-model="invite.message"
                     :isLabel="false"
                     :required="true"/>
        </div>
        <div class="row">
          <div class="col-50">
            <Select
                v-model="invite.interview_type_id"
                placeholder="Тип собеседования"
            />
          </div>
          <div class="col-50">
            <TextInput
                v-model="invite.contact_phone"
                placeholder="Телефон для связи"
                class="invert"
            />
          </div>
        </div>
      </template>

      <template v-slot:footer>
        <div class="btn-wrapper">
          <Button class="btn btn--blue" @click.native="sendInvite">
            Пригласить
          </Button>
        </div>
      </template>
    </modal>
  </div>
</template>
<script>
import LinkWithIcon from '@/components/LinkWithIcon';

export default {
  name: 'InviteApplicant',
  components: {LinkWithIcon},
  props: {
    response: Object,
    vacancy: Object,
  },
  data() {
    return {
      invite: {
        message: '',
        interview_type_id: 20,
        contact_phone: '',
      },
    };
  },
  methods: {
    sendInvite() {
      this.$http.post(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/employer/responses/${this.response.id}/accept`,
          this.invite).
          then(() => {
            this.$refs.inviteForm.closeModal();
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