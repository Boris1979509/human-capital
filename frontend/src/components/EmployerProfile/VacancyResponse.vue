<template>
  <div class="response" :class="{generated: response.cv.type===1, rejected: response.status==='rejected'}">
    <div class="row">
      <div class="col-50">
        <div class="applicant">
          <div class="applicant_avatar">
            <img :src="applicantAvatar" alt="">
          </div>
          <div class="applicant_info">
            <div class="applicant_info_name">
              {{ response.applicant.first_name }} {{ response.applicant.last_name }}
            </div>
            <div class="applicant_info_date">{{ response.created_at | date }}</div>
          </div>
        </div>
      </div>
      <div class="col-50">
        <div class="actions">
          <LinkWithIcon class="blue action" v-if="response.status==='invited'">
            <template slot="icon">
              <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
                <g clip-path="url(#clip0)">
                  <path
                      d="M10.6789 4.26518C11.0844 4.6393 11.1092 5.27061 10.7343 5.67525L5.49566 11.329C4.68436 12.2045 3.28967 12.1794 2.51058 11.2752L0.241734 8.64188C-0.118314 8.22399 -0.0706959 7.59399 0.348093 7.23472C0.766882 6.87545 1.39825 6.92296 1.7583 7.34085L4.02715 9.97414L9.26577 4.32044C9.6407 3.91579 10.2734 3.89105 10.6789 4.26518Z"
                      fill="#3D75E4"/>
                  <path
                      d="M15.6653 4.25287C16.0776 4.6195 16.114 5.25025 15.7466 5.66169L10.6319 11.389C9.7379 12.3902 8.107 12.1173 7.58503 10.8892C7.53037 10.7659 7.50001 10.6295 7.50001 10.486C7.50001 9.93486 7.94772 9.48811 8.50001 9.48811C8.82557 9.48811 9.1148 9.64336 9.2974 9.8837L14.2534 4.33401C14.6208 3.92256 15.2529 3.88624 15.6653 4.25287Z"
                      fill="#3D75E4"/>
                </g>
                <defs>
                  <clipPath id="clip0">
                    <rect width="16" height="16" fill="white"/>
                  </clipPath>
                </defs>
              </svg>

            </template>
            <template slot="text">Приглашен на собеседование</template>
          </LinkWithIcon>
          <LinkWithIcon class="red action" v-if="response.status==='rejected'">
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
            <template slot="text">Отказано</template>
          </LinkWithIcon>
          <InviteApplicant @response-status-changed="$emit('response-status-changed')" v-if="response.status==='send'"
                           :response="response" :vacancy="vacancy" class="action"/>
          <RejectApplicant @response-status-changed="$emit('response-status-changed')"
                           v-if="response.status==='send' || response.status==='invited'" :response="response"
                           :vacancy="vacancy" class="action"/>
        </div>
      </div>
    </div>
    <div class="row">
      <VacancyResponseCv :response="response"/>
    </div>
  </div>
</template>

<script>
import InviteApplicant from '@/components/EmployerProfile/InviteApplicant';
import RejectApplicant from '@/components/EmployerProfile/RejectApplicant';
import VacancyResponseCv from '@/components/EmployerProfile/VacancyResponseCv';
import LinkWithIcon from '@/components/LinkWithIcon';

export default {
  name: 'VacancyResponse',
  components: {LinkWithIcon, VacancyResponseCv, RejectApplicant, InviteApplicant},
  props: {
    response: Object,
    vacancy: Object,
  },
  computed: {
    applicantAvatar() {
      return this.response.applicant.avatar ? this.response.applicant.avatar.url : require('@/assets/img/avatar.jpg');
    },
  },
};
</script>

<style scoped lang="scss">
.response {
  margin-bottom: 42px;
}

.applicant {
  display: flex;

  &_avatar, &_avatar img {
    margin-right: 13px;
    width: 60px;
    height: 60px;
    border-radius: 50%;
  }

  &_info {
    flex-direction: column;
    display: flex;

    &_name {
      font-style: normal;
      font-weight: 800;
      font-size: 16px;
      line-height: 20px;
      color: #04153E;
      margin-bottom: 4px;
    }

    &_date {
      font-style: normal;
      font-weight: normal;
      font-size: 13px;
      line-height: 16px;
      color: #04153E;
      opacity: 0.48;
    }
  }
}

.actions {
  display: flex;
  flex-direction: column;
  align-items: flex-start;

  .action {
    margin-bottom: 16px;
  }
}

.response.generated {
  border: 1px solid #E4EDFB;
  box-sizing: border-box;
  border-radius: 16px;
  padding: 20px;
}
.rejected{
  opacity: 0.48;
}
</style>