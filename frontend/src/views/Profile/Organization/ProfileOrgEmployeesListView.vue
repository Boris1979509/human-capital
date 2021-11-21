<template>
  <div class="employees_list_wrapper">
    <div class="employees_list">
      <div
        class="add_employee"
        @click="
          $router.push({
            name: 'ProfileOrgAddEmployeeView',
            params: { target: 'add' },
          })
        "
      >
        <div class="add_employee_icon">
          <Icon xlink="add" viewport="0 0 48 48" />
        </div>
        <div class="add_employee_text">
          Добавить сотрудника
        </div>
      </div>
      <div class="employee" v-for="employee of orgEmployees" :key="employee.id">
        <div
          class="employee_avatar_edit"
          @click="
            $router.push({
              name: 'ProfileOrgEditEmployeeView',
              params: { target: 'edit', id: employee.id },
            })
          "
        >
          <Icon xlink="edit" viewport="0 0 16 16" />
        </div>
        <div class="employee_avatar">
          <img
            v-if="employee && employee.avatar && employee.avatar.url"
            :src="employee.avatar.url"
            alt=""
          />
          <img v-else src="@/assets/svg/user.svg" alt="" />
        </div>
        <div class="employee_name">
          {{ employee.first_name }}<br />
          {{ employee.last_name }}
        </div>

        <div class="employee_position" v-if="employee.approved">
          {{ employee.position }}
        </div>
        <div class="employee_position not_published" v-else>Не опубликован</div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfileOrgEmployeesListView',
  data: function() {
    return {
      orgEmployees: [],
    };
  },
  computed: {
    orgInfo: function() {
      return this.$organization;
    },
  },
  watch:{
    orgInfo(){
      if (this.orgInfo.length !== 0) {
        this.orgEmployees = this.orgInfo[0].employees;
      }
    }
  },
  created() {
    if (this.orgInfo.length !== 0) {
      this.orgEmployees = this.orgInfo[0].employees;
    }
  },
};
</script>
<style lang="scss" scoped>
.employees_list_wrapper {
  .employees_list {
    display: grid;
    grid-template-columns: repeat(auto-fill, 211px);
    grid-gap: 32px;
    justify-content: center;
    .employee {
      line-height: 1;
      position: relative;
      &_avatar_edit {
        position: absolute;
        display: flex;
        justify-content: center;
        align-items: center;
        width: 30px;
        height: 30px;
        border-radius: 50%;
        background: rgba(4, 21, 62, 0.52);
        &:hover {
          background: rgba(4, 21, 62, 0.82);
        }
        top: -9px;
        left: 71px;
        z-index: 1;
        cursor: pointer;
      }
      &_avatar {
        width: 112px;
        height: 112px;
        display: inline-block;
        border-radius: 50%;
        margin-bottom: 18px;
        overflow: hidden;
        position: relative;
        img {
          width: 100%;
          height: 100%;
        }
      }
      &_name {
        font-family: Golos;
        font-style: normal;
        // max-width: 211px;
        font-weight: 800;
        font-size: 20px;
        line-height: 24px;
        margin-bottom: 4px;
        color: rgba(4, 21, 62, 1);
      }
      &_position {
        font-family: Golos;
        font-style: normal;
        font-weight: normal;
        font-size: 16px;
        line-height: 20px;
        color: rgba(4, 21, 62, 0.48);
        
        &.not_published {
          color: var(--color-lebowski);
        }
      }
    }
    .add_employee {
      width: 112px;
      height: 202px;

      cursor: pointer;
      .add_employee_icon {
        display: flex;
        justify-content: center;
        align-items: center;
        background: #f6f9fe;
        width: 112px;
        height: 112px;
        margin-bottom: 16px;
        border-radius: 50%;
      }
      .add_employee_text {
        display: inline-block;
        font-family: Golos;
        font-style: normal;
        font-weight: 800;
        font-size: 20px;
        line-height: 24px;
        text-align: left;
        color: #214eb0;
      }
    }
  }
}
</style>
