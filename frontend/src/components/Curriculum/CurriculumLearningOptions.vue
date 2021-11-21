<template>
  <div class="learning_options" v-if="options.length">
    <h1>Вариант обучения</h1>
    <FilterTabs
        :items="auditories"
        v-model="selectedAuditory"
    />
    <DataTable
        :columns="columns"
        :rows="rows"
    />
  </div>
</template>

<script>
import DataTable from '../DataTable';
import FilterTabs from '../FilterTabs';

export default {
  name: 'CurriculumLearningOptions',
  components: {DataTable, FilterTabs},
  props: {
    options: Array,
  },
  mounted() {
    this.selectedAuditory = this.auditories[0].id;
  },
  computed: {
    auditories() {
      return this.options.reduce((result, option) => {
        result.push(option.auditory);
        return result;
      }, []);
    },
    rows() {
      return this.options.filter(option => option.auditory.id === this.selectedAuditory).map(option => {
        return Object.assign({}, {
          ...option,
          edu_form: option?.edu_form?.name,
        });
      });
    },
  },
  data() {
    return {
      columns: [
        {
          label: 'Форма обучения',
          field: 'edu_form',
          sortable: false,
          tdClass: 'text-bold',
        },
        {
          label: 'Проходной балл',
          label_additional: 'Платное/бюджет',
          html: true,
          field: 'passing_score',
          sortable: true,
        },
        {
          label: 'Места',
          label_additional: 'Платное/бюджет',
          field: 'available_places',
          sortable: true,
        },
        {
          label: 'Стоимость',
          label_additional: 'За год',
          field: 'cost',
          sortable: true,
        },
        {
          label: 'Срок обучения',
          field: 'how_long',
          sortable: true,
        },
        {
          label: 'Старт обучения',
          field: 'start_date',
          sortable: true,
        },
      ],
      selectedAuditory: null,
    };
  },
};
</script>

<style scoped lang="scss">
.learning_options {
  margin-top: 72px;
}

h1 {
  font-weight: 800;
  font-size: 32px;
  line-height: 36px;
  margin-bottom: 32px;
}
</style>
