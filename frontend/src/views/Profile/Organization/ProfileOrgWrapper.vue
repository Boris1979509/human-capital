<template>
  <div class="profile">
    <Breadcrumbs/>

    <div class="wrapper_user_tabs">

      <div class="user">
        <Avatar :id="org_id" :progress="currentProgress" type="organization"/>

        <div class="user__info">
          <h1 class="title" v-if="full_name">
            {{ full_name }}
          </h1>

          <div class="user__position" v-if="inst_type_name">
            {{ inst_type_name }}
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
import Avatar from '@/components/InputComponents/Avatar';
import Tooltip from '@/components/Tooltip';

export default {
  name: 'ProfileOrgWrapper',

  components: {
    Avatar,
    Tooltip,
  },

  computed: {
    orgInfo: function() {
      return this.$organization[0];
    },
    inst_type: function() {
      return this.$dictionaries.inst_type;
    },
  },

  watch: {
    orgInfo: function(val) {
      this.full_name = val?.full_name;
      this.short_name = val?.short_name;
      this.org_id = val?.id;
      this.inst_type_name = this.inst_type?.find(current => current.id === val?.inst_type_id).name;
      this.currentProgress = val?.progress;
    },
  },

  mounted() {
    if (this.orgInfo) {
      this.full_name = this.orgInfo?.full_name;
      this.short_name = this.orgInfo?.short_name;
      this.org_id = this.orgInfo?.id;
      this.inst_type_name = this.inst_type?.find(current => current.id === this.orgInfo?.inst_type_id).name;
      this.currentProgress = this.orgInfo?.progress;
    }
  },

  data: function() {
    return {
      org_id: '',
      full_name: '',
      inst_type_name: '',

      currentProgress: 0,

      tabs: [
        {
          id: 1,
          name: 'Информация',
          route: '/profile/org/info',
        },
        {
          id: 2,
          name: 'Программы обучения',
          route: '/profile/org/programs',
        },
        {
          id: 3,
          name: 'Сотрудники',
          route: '/profile/org/employees',
        },
        {
          id: 4,
          name: 'Календарь',
          route: '/profile/org/calendar',
        },
        {
          id: 5,
          name: 'Журнал',
          route: '/profile/org/journal',
        },
        {
          id: 6,
          name: 'Настройки',
          route: '/profile/org/settings',
        },
      ]
    }
  },
}
</script>
