<template>
  <div class="row">
    <div class="col-33">
      <TextInput
          placeholder="Поиск"
          v-model="filters.q"
          class="invert"
          :required="true"
          classSpecial="autocomplete"
      />
    </div>

    <div class="col-33">
      <MultiSelect :array="type_branch"
                   :pre-selected="filters.branch_id"
                   placeholder="Отрасль"
                   @select="filters.branch_id = $event"
      />
    </div>

    <div class="col-33">
      <MultiSelect :array="cities"
                   :pre-selected="filters.city_id"
                   placeholder="Город"
                   @select="filters.city_id = $event"
      />
    </div>
  </div>
</template>
<script>
import MultiSelect from '@/components/InputComponents/MultiSelect';

export default {
  name: 'EmployersFilters',
  components: {MultiSelect},
  computed: {
    type_branch: function() {
      return this.$dictionaries.type_branch;
    },
    cities: function() {
      return this.$cities.data;
    },
  },
  props: {
    filters: Object,
  },
};
</script>