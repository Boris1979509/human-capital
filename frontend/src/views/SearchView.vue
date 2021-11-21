<template>
  <div class="page">
    <h1 class="title">Результаты поиска</h1>
    <SearchInput class="input" @search="search" :value="query"/>
    <div class="results-count">
      Результатов: {{ count }}
    </div>
    <div class="row">
      <div class="col-50">
        <MultiSelect placeholder="Показывать" all-label="Все разделы" :array="contentTypes"
                     @select="selectedTypes = $event"/>
      </div>
    </div>
    <div class="row">
      <div class="col-100" v-for="item in items" :key="item.id+item.type">
        <SearchResultItem :item="item"/>
      </div>
    </div>
  </div>
</template>

<script>
import SearchInput from '@/components/InputComponents/SearchInput';
import MultiSelect from '@/components/InputComponents/MultiSelect';
import SearchResultItem from '@/components/SearchResultItem';

export default {
  name: 'SearchView',
  components: {SearchResultItem, MultiSelect, SearchInput},
  data() {
    return {
      query: '',
      items: [],
      contentTypes: [
        {name: 'Журнал', id: 'content'},
        {name: 'Работодатели', id: 'employer'},
        {name: 'Вакансии', id: 'vacancy'},
        {name: 'Образовательные программы', id: 'curriculum'},
        {name: 'Образовательные организации', id: 'institution'},
      ],
      selectedTypes: [],
    };
  },
  computed: {
    count() {
      return this.items.length;
    },
  },
  mounted() {
    if (this.$route.query.query) {
      this.query = this.$route.query.query;
      this.search(this.query);
    }
  },
  methods: {
    search(query) {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/search/`, {
        params: {query: query, filter: this.selectedTypes},
      }).then(res => {
        this.items = res.data;
      });
    },
  },
};
</script>
<style scoped>
.title {
  margin-bottom: 32px !important;
}

.input {
  margin-bottom: 32px;
}

.results-count {
  font-weight: normal;
  font-size: 16px;
  line-height: 24px;
  color: #04153E;
  opacity: 0.48;
  margin-bottom: 16px;
}
</style>
