<template>
  <div>
    <div class="row">
      <VacanciesFilters :filters="filters"/>
    </div>
    <div class="row" style="min-height: 330px">
      <div class="col-50" v-for="vacancy in vacancies" :key="vacancy.id">
        <VacancyCard :vacancy="vacancy"/>
      </div>
      <infinite-loading @infinite="getVacancies" :identifier="infiniteId">
        <div slot="spinner">
          <div class="is-loading">
            <LoadingRing/>

            <span>
              Загрузка...
            </span>
          </div>
        </div>
        <div slot="no-more"></div>
        <div slot="no-results">
          <NothingFound text="Ничего не найдено"/>
        </div>
      </infinite-loading>
    </div>
  </div>
</template>

<script>
import VacanciesFilters from '@/components/Vacancies/VacanciesFilters';
import VacancyCard from '@/components/Cards/VacancyCard';
import LoadingRing from '@/components/LoadingRing';
import InfiniteLoading from 'vue-infinite-loading';

export default {
  name: 'VacanciesCatalog',
  components: {LoadingRing, VacancyCard, VacanciesFilters, InfiniteLoading},
  data() {
    return {
      filters: {
        q: '',
        employer: '',
        city_id: [],
        profession_id: [],
        published_from: null,
        published_to: null,
        skills: [],
        salary_min: 0,
        internship: false,
      },
      pagination: {
        limit: 4,
        page: 1,
      },
      vacancies: [],
      infiniteId: +new Date(),
    };
  },
  computed: {
    sanitizedFilters() {
      let filters = Object.assign({},
          ...this.filters,
      );
      if (!filters.q) {
        delete filters.q;
      }
      if (!filters.employer) {
        delete filters.employer;
      }
      if (filters.internship) {
        filters.internship = 1;
      } else {
        delete filters.internship;
      }
      return filters;
    },
  },
  watch: {
    filters: {
      deep: true,
      handler: function() {
        this.pagination.page = 1;
        this.vacancies = [];
        this.infiniteId += 1;
      },
    },
  },
  methods: {
    getVacancies($state) {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/vacancies`, {
        params: {
          ...this.sanitizedFilters,
          ...this.pagination,
        },
      }).
          then(({data}) => {
            if (data.data.length) {
              this.pagination.page += 1;
              this.vacancies.push(...data.data);
              $state?.loaded();
            } else {
              $state?.complete();
            }
          });
    },
  },

};
</script>

<style scoped lang="scss">
.infinite-loading-container {
  width: 100%;

  .is-loading {
    width: 100%;
    height: 50vh;

    display: flex;
    flex-direction: column;
    align-items: center;
    justify-content: center;

    span {
      display: block;

      font-weight: normal;
      font-size: 16px;
      line-height: 20px;
      text-align: center;
      color: var(--main-color-dark);

      margin-top: 12px;
    }
  }
}
</style>