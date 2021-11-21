<template>
  <div class="page">
    <Breadcrumbs :margin="24"/>
    <OrganizationsHeadSection/>
    <FilterTabs :items="tabs" v-model="selectedTab" class="tabs-left" style="margin-top: 20px;"/>
    <template v-if="selectedTab===1">
      <OrganizationsFilters :filters="filters" :preselectedTypes="this.$route.params.type" :displayType="displayType"/>
      <div class="row" v-if="displayType.isList">
        <div class="col-25" v-for="organization in organizations" :key="organization.id">
          <OrganizationCard :organization="organization"/>
        </div>
        <infinite-loading @infinite="getOrganizations" :identifier="infiniteId">
          <div slot="spinner">
            <div class="is-loading">
              <LoadingRing/>

              <span>
                  Загрузка...
                </span>
            </div>
          </div>
          <div slot="no-more"></div>
          <div slot="no-results"></div>
        </infinite-loading>
      </div>
      <div class="row" v-if="displayType.isMap">
        <Map :markers="organizationsMarkers" style="width: 100%;"/>
      </div>
    </template>

    <template v-if="selectedTab===2">
      <CardsFilter/>
    </template>
  </div>
</template>

<script>
import InfiniteLoading from 'vue-infinite-loading';
import LoadingRing from '@/components/LoadingRing';
import FilterTabs from '@/components/FilterTabs';
import Map from '@/components/Map';

export default {
  name: 'MainViewOrganizations',

  components: {
    Map,
    FilterTabs,
    InfiniteLoading,
    LoadingRing,
  },

  data() {
    return {
      filters: {
        city: null,
        type: this.$route.params.type?.map(item => item.id),
      },
      pagination: {
        limit: 12,
        page: 1,
      },
      displayType: {
        isList: true,
        isMap: false,
      },
      organizations: [],
      infiniteId: +new Date(),
      isMapLoaded: false,
      tabs: [
        {
          name: 'Учебные заведения',
          id: 1,
        },
        {
          name: 'Образовательные программы',
          id: 2,
        },
      ],
      selectedTab: 1,
    };
  },
  computed: {
    organizationsMarkers() {
      return this.organizations.reduce((markers, organization) => {
        // eslint-disable-next-line no-prototype-builtins
        const organizationContacts = organization.contacts.filter(contact => contact.hasOwnProperty('coords')) || [];
        const organizationMarkers = organizationContacts.map(contact => {
          return {
            coords: contact.coords || [37.626543, 55.753823],
            title: contact.name,
            subtitle: organization.short_name,
            description: contact.address,
          };
        });
        return markers.concat(organizationMarkers);
      }, []);
    },
  },
  methods: {
    getOrganizations($state) {
      if (this.displayType.isList) {
        this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions`, {
          params: {
            ...this.filters,
            ...this.pagination,
          },
        }).then(({data}) => {
          if (data.data.length) {
            this.pagination.page += 1;
            this.organizations.push(...data.data);
            $state?.loaded();
          } else {
            $state?.complete();
          }
        });
      }

      if (this.displayType.isMap) {
        this.isMapLoaded = false;
        this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions`, {
          params: {
            ...this.filters,
          },
        }).then(res => {
          this.organizations = res.data.data;
        }).finally(() => {
          this.isMapLoaded = true;
        });
      }
    },
  },
  watch: {
    filters: {
      deep: true,
      handler: function() {
        if (this.displayType.isMap) {
          this.getOrganizations();
        }
        if (this.displayType.isList) {
          this.pagination.page = 1;
          this.organizations = [];
          this.infiniteId += 1;
        }
      },
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
