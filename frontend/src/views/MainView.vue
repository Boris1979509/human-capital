<template>
  <div class="page">
    <section class="page__section">
      <HeadSection :region="regionName" :counters="regionCounters"/>
    </section>

    <section class="page__section">
      <PortalSections/>
    </section>

    <section class="page__section">
      <router-link to="/selections">
        <h1 class="title">
          Подборки
          <Icon xlink="link_arrow_big"
                viewport="0 0 12 24"/>
        </h1>
      </router-link>

      <div class="page__description">
        Лучшие материалы, отобранные с учетом ваших<br/> персональных предпочтений
      </div>

      <Compilations/>
    </section>

    <section class="page__section">
      <router-link to="/">
        <h1 class="title">
          Профессии
          <Icon xlink="link_arrow_big"
                viewport="0 0 12 24"/>
        </h1>
      </router-link>

      <div class="page__description">
        Освойте востребованную профессию из актуальной ниши,<br/> чтобы получать лучшие предложения о работе
      </div>

      <ProfessionsCatalog/>
    </section>

    <section class="page__section">
      <router-link to="/institutions">
        <h1 class="title">
          Каталог образования
          <Icon xlink="link_arrow_big"
                viewport="0 0 12 24"/>
        </h1>
      </router-link>

      <div class="page__description">
        Найдите образовательную программу для любого<br/> возраста и уровня подготовки
      </div>

      <EducationCatalog/>
    </section>

    <section class="page__section">
      <router-link to="/">
        <h1 class="title">
          Мнения
          <Icon xlink="link_arrow_big"
                viewport="0 0 12 24"/>
        </h1>
      </router-link>

      <div class="page__description">
        Узнавайте мнение экспертов и знакомьтесь со своими<br /> будущими преподавателями
      </div>

      <Opinions />
    </section>

    <section class="page__section">
      <router-link to="/journal">
        <h1 class="title">
          Журнал
          <Icon xlink="link_arrow_big"
                viewport="0 0 12 24"/>
        </h1>
      </router-link>

      <div class="page__description">
        Узнавайте актуальные новости и читайте статьи<br /> об образовании и карьере в регионе
      </div>

      <JournalCatalog />
    </section>
  </div>
</template>

<script>
import HeadSection from '@/components/MainViewParts/HeadSection';
export default {
  name: 'MainView',
  components: {HeadSection},
  computed: {
    regionName: function() {
      return this.$regionName;
    },
    regionCounters: function() {
      return this.$regionCounters;
    },
  },

  mounted() {
    this.$store.dispatch('GET_INST_TYPES_FROM_SERVER', {
      clear: true,
      params: {
        type: 'inst_type',
        ids: process.env.VUE_APP_PAGE_INDEX_INST_TYPE_IDS,
      },
    });
  },
}
</script>
