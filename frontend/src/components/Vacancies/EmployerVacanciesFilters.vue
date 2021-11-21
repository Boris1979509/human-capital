<template>
  <div class="col-100">
    <div class="row">
      <div class="col-33">
        <MultiSelect :array="cities"
                     :pre-selected="filters.city_id"
                     placeholder="Город"
                     @select="filters.city_id = $event"
        />
      </div>
      <div class="col-33">
        <Range :max="500000"
               :min="0"
               :step="1000"
               :value="filters.salary_min"
               label="Зарплата от"
               @change="filters.salary_min = $event"
        />
      </div>
      <div class="col-33">
        <Checkbox
            id="internship"
            label="Показать только стажировки"
            :margin="24"
            :checked="filters.internship"
            @change="filters.internship = $event"
        />
      </div>
    </div>
  </div>
</template>

<script>
import MultiSelect from '@/components/InputComponents/MultiSelect';

export default {
  name: 'EmployerVacanciesFilters',
  components: {MultiSelect},
  props: {
    filters: Object,
  },
  data() {
    return {
      selectedDateInterval: 3,
      dateIntervals: [
        {
          id: 1,
          name: 'За вчера',
        },
        {
          id: 2,
          name: 'За последние 3 дня',
        },
        {
          id: 3,
          name: 'За последнюю неделю',
        },
        {
          id: 4,
          name: 'За последний месяц',
        },
      ],
      selectedTags: [],
    };
  },
  computed: {
    type_branch: function() {
      return this.$dictionaries.type_branch;
    },
    cities: function() {
      return this.$cities.data;
    },
  },
};
</script>

<style scoped>

</style>