<template>
  <div style="width: 100%;">
    <div class="row">
      <div class="col-50">
        <TextInput
            placeholder="Поиск"
            v-model="filters.q"
            class="invert"
            :required="true"
            classSpecial="autocomplete"
        />
      </div>
      <div class="col-50">
        <TextInput
            placeholder="Работодатель"
            v-model="filters.employer"
            class="invert"
            :required="true"
        />
      </div>
    </div>
    <div class="row">
      <div class="col-33">
        <MultiSelect :array="type_branch"
                     :pre-selected="filters.profession_id"
                     placeholder="Отрасль"
                     @select="filters.profession_id = $event"
        />
      </div>
      <div class="col-33">
        <MultiSelect :array="cities"
                     :pre-selected="filters.city_id"
                     placeholder="Город"
                     @select="filters.city_id = $event"
        />
      </div>
      <div class="col-33">
        <Select :array="dateIntervals"
                     :pre-selected="selectedDateInterval"
                     placeholder="Опубликована"
                     @select="selectedDateInterval = $event"
        />
      </div>
    </div>
    <div class="row">
      <div class="col-50">
        <TagsCloud :tags="filters.skills"
                   @updateTags="filters.skills.push($event)"
                   placeholder="Введите навыки"
                   :margin="0"
        />
      </div>
      <div class="col-50">
        <Range :max="500000"
               :min="0"
               :step="1000"
               :value="filters.salary_min"
               label="Зарплата от"
               @change="filters.salary_min = $event"
        />
      </div>
      <div class="col-50"></div>
      <div class="col-50" style="float:right;">
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
  name: 'VacanciesFilters',
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