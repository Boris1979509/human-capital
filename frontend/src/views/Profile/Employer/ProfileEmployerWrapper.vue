<template>
  <div class="profile">
    <Breadcrumbs/>

    <div class="wrapper_user_tabs">
      <div class="user">
        <Avatar :id="employer_id" :progress="currentProgress" type="employer"/>

        <div class="user__info">
          <h1 class="title" v-if="name">
            {{ name }}
          </h1>

          <div class="user__position" v-if="branch">
            {{ branch }}
          </div>

          <div class="user__progress">
            Страница заполнена на {{ currentProgress }}%

            <Tooltip activator-hover>
              <div slot="activator">
                <Icon xlink="info"
                      viewport="0 0 16 16"
                />
              </div>

              <span>Добавьте недостающие данные, чтобы заполнить шкалу</span>
            </Tooltip>
          </div>
        </div>
      </div>

      <Tabs :tabs="tabs"/>
    </div>

    <slot/>
  </div>
</template>

<script>
import Avatar from "@/components/InputComponents/Avatar";
import Tooltip from "@/components/Tooltip";

export default {
  name: 'ProfileEmployerWrapper',

  components: {
    Avatar,
    Tooltip,
  },

  computed: {
    employerInfo: function() {
      return this.$employer;
    },
    type_branch: function() {
      return this.$dictionaries.type_branch;
    },
  },

  watch: {
    employerInfo: function(val) {
      this.name = val?.name;
      this.employer_id = val?.id;
      this.branch = this.type_branch.filter(item => item.id === val?.branch_id)[0].name;
      this.currentProgress = val?.progress;
    },
  },

  created() {
    if (this.employerInfo.length !== 0) {
      this.name = this.employerInfo?.name;
      this.employer_id = this.employerInfo?.id;
      this.branch = this.type_branch.filter(item => item.id === this.employerInfo?.branch_id)[0].name;
      this.currentProgress = this.employerInfo?.progress;
    }
  },

  data: function() {
    return {
      name: '',
      branch: '',
      employer_id: null,

      currentProgress: 0,

      tabs: [
        {
          id: 1,
          name: 'Общая информация',
          route: '/profile/employer/info',
        },
        {
          id: 2,
          name: 'HR',
          route: '/profile/employer/vacancies',
        },
        {
          id: 4,
          name: 'Календарь',
          route: '/profile/employer/calendar',
        },
        {
          id: 5,
          name: 'Журнал',
          route: '/profile/employer/journal',
        },
        {
          id: 6,
          name: 'Настройки',
          route: '/profile/employer/settings',
        },
      ]
    }
  },
}
</script>
