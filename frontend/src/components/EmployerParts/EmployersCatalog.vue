<template>
  <div class="popular-programs">
    <div class="programs-filter">
      <EmployersFilters :filters="filters"/>

      <div class="row" style="min-height: 330px">
        <div class="col-25" v-for="employer in employers" :key="employer.id">
          <EmployerCard :employer="employer"/>
        </div>
        <infinite-loading @infinite="getEmployers" :identifier="infiniteId">
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
  </div>
</template>

<script>

import EmployerCard from '@/components/Cards/EmployerCard';
import EmployersFilters from '@/components/EmployerParts/EmployersFilters';
import LoadingRing from '@/components/LoadingRing';
import InfiniteLoading from 'vue-infinite-loading';

export default {
  name: 'EmployersCatalog',

  components: {
    InfiniteLoading,
    LoadingRing,
    EmployersFilters,
    EmployerCard,
  },

  watch: {
    filters: {
      deep: true,
      handler: function() {
        this.pagination.page = 1;
        this.employers = [];
        this.infiniteId += 1;
      },
    },
  },

  data() {
    return {
      filters: {
        q: '',
        branch_id: [],
        city_id: [],
      },
      pagination: {
        limit: 12,
        page: 1,
      },
      employers: [],
      infiniteId: +new Date(),
    };
  },
  computed: {
    sanitizedFilters() {
      return Object.assign({},
          ...this.filters.q && {q: this.filters.q},
          {
            branch_id: this.filters.branch_id,
            city_id: this.filters.city_id
          },
      );
    },
  },

  methods: {
    getEmployers($state) {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/employer`, {
        params: {
          ...this.sanitizedFilters,
          ...this.pagination,
        },
      }).
          then(({data}) => {
            if (data.data.length) {
              this.pagination.page += 1;
              this.employers.push(...data.data);
              $state?.loaded();
            } else {
              $state?.complete();
            }
          });
    },
  },
};
</script>
<style lang="scss">
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