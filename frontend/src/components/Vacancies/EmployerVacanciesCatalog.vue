<template>
  <div class="vacancies">
    <div class="row">
      <div class="col-100">
        <h1>Вакансии компании</h1>
      </div>
    </div>
    <div class="row filters">
      <EmployerVacanciesFilters :filters="filters"/>
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
import VacancyCard from '@/components/Cards/VacancyCard';
import LoadingRing from '@/components/LoadingRing';
import InfiniteLoading from 'vue-infinite-loading';
import EmployerVacanciesFilters from '@/components/Vacancies/EmployerVacanciesFilters';

export default {
  name: 'EmployerVacanciesCatalog',
  components: {EmployerVacanciesFilters, LoadingRing, VacancyCard, InfiniteLoading},
  props: {
    employer: Object,
  },
  data() {
    return {
      filters: {
        city_id: [],
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
      if (filters.internship) {
        filters.internship = 1;
      } else {
        delete filters.internship;
      }
      filters.employer_id = this.employer.id;
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

h1 {
  font-weight: 800;
  font-size: 32px;
  line-height: 36px;
  color: #04153E;
}

.filters {
  margin-top: 32px;
}
.vacancies{
  margin-top: 36px;
}
</style>