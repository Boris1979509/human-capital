<template>
  <div class="row organizations-filters">
    <div class="col-33">
      <Select :array="cities.data" @select="filters.city = $event" placeholder="Город"/>
    </div>

    <div class="col-33">
      <MultiSelect :array="types"
                   :pre-selected="preselectedTypes"
                   placeholder="Уровень образования"
                   @select="filters.type = $event"/>
    </div>

    <div class="col-25">
      <div class="organizations-filters__map-view" v-if="displayType.isList">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M14 6C14 9.15381 12.2402 11.8955 10.7637 13.6406C10.4072 14.0615 9.77734 14.1133 9.35352 13.7578C8.93262 13.4004 8.87988 12.7695 9.23633 12.3477C10.4971 10.8584 12 8.54785 12 6C12 3.79443 10.2061 2 8 2C5.79443 2 4 3.79443 4 6C4 10.4492 8.57959 14.1826 8.62549 14.2197C9.05566 14.5654 9.125 15.1943 8.77979 15.625C8.58203 15.8721 8.2915 16 7.99902 16C7.77979 16 7.55957 15.9287 7.37549 15.7812C7.15576 15.6055 2 11.418 2 6C2 2.69141 4.69141 0 8 0C11.3086 0 14 2.69141 14 6ZM8 4.49982C7.17157 4.49982 6.5 5.17139 6.5 5.99982C6.5 6.82825 7.17157 7.49982 8 7.49982C8.82843 7.49982 9.5 6.82825 9.5 5.99982C9.5 5.17139 8.82843 4.49982 8 4.49982Z"
              fill="#3D75E4"/>
        </svg>
        <div class="organizations-filters__map-view_link" @click="changeDisplayType">
          Показать на карте
        </div>
      </div>
      <div class="organizations-filters__map-view" v-if="displayType.isMap">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path fill-rule="evenodd" clip-rule="evenodd"
                d="M7 14V11C7 9.89543 6.10457 9 5 9H2C0.895431 9 0 9.89543 0 11V14C0 15.1046 0.89543 16 2 16H5C6.10457 16 7 15.1046 7 14ZM5 2L2 2L2 5H5V2ZM5 11H2L2 14H5V11ZM14 2L11 2V5H14V2ZM14 11H11V14H14V11ZM2 0C0.895431 0 0 0.89543 0 2V5C0 6.10457 0.89543 7 2 7H5C6.10457 7 7 6.10457 7 5V2C7 0.895431 6.10457 0 5 0H2ZM9 2C9 0.89543 9.89543 0 11 0H14C15.1046 0 16 0.895431 16 2V5C16 6.10457 15.1046 7 14 7H11C9.89543 7 9 6.10457 9 5V2ZM11 9C9.89543 9 9 9.89543 9 11V14C9 15.1046 9.89543 16 11 16H14C15.1046 16 16 15.1046 16 14V11C16 9.89543 15.1046 9 14 9H11Z"
                fill="#3D75E4"/>
        </svg>
        <div class="organizations-filters__map-view_link" @click="changeDisplayType">
          Показать плиткой
        </div>
      </div>
    </div>
  </div>

</template>

<script>
import MultiSelect from '@/components/InputComponents/MultiSelect';

export default {
  name: 'OrganizationsFilters',

  components: {
    MultiSelect,
  },

  props: {
    filters: Object,
    displayType: Object,
    preselectedTypes: Array,
  },

  computed: {
    cities: function() {
      return this.$cities;
    },
    types: function() {
      return this.$dictionaries.inst_type;
    },
  },

  methods: {
    changeDisplayType() {
      this.displayType.isList = !this.displayType.isList;
      this.displayType.isMap = !this.displayType.isMap;
    },
  },
};
</script>
