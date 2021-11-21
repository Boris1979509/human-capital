<template>
  <div class="inner-page institution-page" v-if="loaded">
    <Breadcrumbs :margin="24"/>

    <section class="profile__section" v-if="$route.params.fromComponent">
      <div class="row">
        <div class="col-50">
          <div class="btn btn--blue" @click="$router.push({
            name: $route.params.fromComponent,
              params: {
                id: $route.params.id,
              }
            })">
            Обратно к редактированию
          </div>
        </div>
      </div>
    </section>

    <section class="page__section">
      <div class="row">
        <div class="col-66">
          <h1 class="institution-page__title title">
            {{ institution.full_name }}
          </h1>

          <p class="institution-page__type">
            {{ institution.type.name }}
            <template v-if="institution.diploma">, {{ institution.diploma.name }}</template>
          </p>

          <div class="institution-page__avatar" v-if="institution.avatar">
            <img :src="institution.avatar ? institution.avatar.url : ''" alt="">
          </div>

          <div class="institution-page__desc" v-if="institution.description" v-html="institution.description">
          </div>
        </div>

        <div class="col-33">
          <Rating
              :rating="institution.rating"
              rateableType="institution"
              :rateableId="institution.id"
              :rating_user="institution.rating_user"
              @ratingChanged="institution.rating_user=$event"
          />

          <Button class="btn--light">
            Связаться
          </Button>

          <Favorite
              v-if="institution.id"
              :initialFavorited="institution.is_favorited"
              type="institution"
              :itemId="institution.id"
              style="margin-top: 26px;"
          />
          <SocialSharing
              :link="`https://hcap.d.rusatom.dev/institutions/${$route.params.id}`"
              :title="institution.full_name"
              :image="institution.avatar"
              :description="institution.description"
              :label="true"
          />
          <div class="info-list" v-if="infoList.length !== 0">
            <div class="row">
              <div class="col-50" v-for="(item, key) in infoList" :key="key">
                <div class="info-list__item" v-if="item.number">
                  <div class="info-list__item-number">
                    {{ item.number }}
                  </div>

                  <div class="info-list__item-description">
                    {{ item.name }}
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </section>

    <section class="page__section">
      <h2 class="institution-page__title title">
        Образовательные программы
      </h2>

      <CardsFilter :institution-id="$route.params.id" :see-more="true"/>
    </section>

    <section class="page__section" v-if="employees.length !== 0">
      <h2 class="institution-page__title title">
        Деканат и преподаватели
      </h2>

      <EmployeesSlider :array="employees"/>
    </section>

    <section class="page__section">
      <h2 class="institution-page__title title">
        Журнал
      </h2>

      <div class="filter_panel">
        <JournalFilter :selected-filter-type.sync="filterType"/>
      </div>

      <EventsCatalog :array="journal"/>
    </section>

    <section class="page__section" v-if="institution.contacts">
      <h2 class="institution-page__title title">
        Контакты
      </h2>

      <div class="row">
        <div class="col-33">
          <div class="contact" v-for="(item, key) in institution.contacts" :key="key">
            <h5 class="contact__title title">
              {{ item.name }}
            </h5>

            <div class="contact__item">
              <Icon xlink="location"
                    viewport="0 0 16 16"/>
              {{ item.address }}
            </div>

            <div class="contact__item" v-for="phone in item.phones" :key="phone">
              <Icon xlink="phone" viewport="0 0 16 16"/>

              <a :href="`tel:${phone}`">
                {{ phone }}
              </a>
            </div>

            <div class="contact__item" v-for="email in item.emails" :key="email">
              <Icon xlink="email" viewport="0 0 16 16"/>

              <a :href="`mailto:${email}`">
                {{ email }}
              </a>
            </div>
          </div>

          <div class="contact__website" v-if="institution.website">
            <div class="contact__label" style="margin-bottom: 5px;">
              Официальный сайт
            </div>

            <a :href="institution.website" title="Официальный сайт" class="contact__link">
              {{ institution.website }}
            </a>
          </div>

          <div class="social" style="margin-top: 24px;" v-if="institution.link_vk || institution.link_fb">
            <div class="contact__label">
              Мы в соцсетях
            </div>

            <a :href="institution.link_vk" v-if="institution.link_vk" title="Вконтакте" class="social__link">
              <Icon xlink="vk" viewport="0 0 32 32"/>
            </a>

            <a :href="institution.link_fb" v-if="institution.link_fb" title="Фейсбук" class="social__link">
              <Icon xlink="fb" viewport="0 0 32 32"/>
            </a>
          </div>
        </div>

        <div class="col-66">
          <div class="map">
            <Map :markers="institutionMarkers">
            </Map>
          </div>
        </div>
      </div>
    </section>
  </div>
</template>

<script>
import Favorite from '../components/Favorite';
import Rating from '@/components/Rating';
import Map from '@/components/Map';

export default {
  name: 'InstitutionView',
  components: {Map, Favorite, Rating},
  mounted() {
    this.getJournalItems();

    this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions/${this.$route.params.id}`).
        then(resolve => {
          this.institution = resolve.data.data;

          this.updateBreadcrumbs();

          if (resolve.data.data.count_curricula) this.infoList.push(
              {name: 'учебных программ', number: resolve.data.data.count_curricula});
          if (resolve.data.data.avg_score) this.infoList.push(
              {name: 'средний проходной балл', number: resolve.data.data.avg_score});
          if (resolve.data.data.avg_salary) this.infoList.push(
              {name: 'средняя зарплата выпускника', number: resolve.data.data.avg_salary});
          if (resolve.data.data.rating_students) this.infoList.push(
              {name: 'рейтинг по оценкам студентов', number: resolve.data.data.rating_students});
          if (resolve.data.data.rating_employers) this.infoList.push(
              {name: 'рейтинг по оценкам работодателей', number: resolve.data.data.rating_employers});
          if (resolve.data.data.rate_employment) this.infoList.push(
              {name: 'процент трудоустройства', number: resolve.data.data.rate_employment});
          if (resolve.data.data.count_students) this.infoList.push(
              {name: 'студентов учится', number: resolve.data.data.count_students});

          this.$http.get(
              `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions/${this.$route.params.id}/employees`).
              then((resolve) => {
                this.employees = resolve.data.data;
              }).
              finally(() => {
                this.loaded = true;
              });
        });
  },

  watch: {
    filterType: function(val) {
      if (val) {
        this.queryParams.type = val;
        this.getJournalItems();
      } else {
        delete this.queryParams.type;
        this.getJournalItems();
      }
    },
  },

  data: function() {
    return {
      avatar: require('@/assets/svg/user.svg'),

      queryParams: {
        order_by: '-published_at',
        page: 1,
        per_page: 3,
        institution_id: this.$route.params.id,
      },
      filterType: 0,

      institution: [],
      infoList: [],
      employees: [],
      journal: [],

      loaded: false,

      journalType: null,
    };
  },

  computed: {
    institutionMarkers() {
      if (!this.institution.id) {
        return [];
      }
      return this.institution.contacts.map(contact => {
        return {
          coords: contact.coords,
          image: this.institution.avatar ? this.institution.avatar.url : null,
          title: contact.name,
          subtitle: this.institution.type.name,
          description: contact.address
        };
      });
    },
  },

  methods: {
    getJournalItems: function() {
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/journal`, {
        params: {...this.queryParams},
      }).then((response) => {
        if (response.status === 200) {
          const {data: {data: data}} = response;

          this.journal = data;
        }
      });
    },

    updateBreadcrumbs() {
      this.$route.meta.breadcrumb = this.$route.meta.breadcrumb.filter(o => o.type !== 'inst');
      this.$route.meta.breadcrumb.push({
        type: 'inst',
        name: this.institution.full_name,
      });
    },
  },
};
</script>

<style lang="scss">
.filter_panel {
  display: flex;
  margin-bottom: 32px;

  .item {
    font-family: Golos;
    font-size: 16px;
    font-style: normal;
    font-weight: 500;
    line-height: 20px;
    letter-spacing: 0em;
    text-align: left;
    margin-left: 20px;
    cursor: pointer;

    &:first-child {
      margin-left: 0;
    }

    &.selected:after {
      content: '';
      background: var(--main-color);
      display: block;
      height: 4px;
      border-radius: 2px;
      margin-top: 8px;
    }
  }
}
</style>
