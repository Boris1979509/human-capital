<template>
  <div class="inner-page">
    <Breadcrumbs :margin="24"/>

    <section class="page__section">
      <h1 class="title">
        {{ pageType($route.meta.type) }}
      </h1>

      <p>
        Мы собрали все материалы, которые могут быть полезны,<br/>
        чтобы выбрать профессию и учебное заведение по душе
      </p>
    </section>

    <section class="page__section">
      <h2 class="title">
        Мероприятия
      </h2>

      <div class="page__description">
        Погрузитесь в рабочую атмосферу во время дня без турникетов<br/>
        или подберите ярмарку вакансий подходящей отрасли
      </div>

      <EventsCatalog :filter-type="$route.meta.type"/>
    </section>

    <section class="page__section">
      <h2 class="title">
        Куда пойти учиться
      </h2>

      <div class="page__description">
        Найдите образовательную программу для любого<br/>
        возраста и уровня подготовки
      </div>

      <WhereToGo :filter="$route.meta.type"/>
    </section>

    <section class="page__section">
      <h2 class="title">
        Популярные образовательные программы
      </h2>

      <div class="page__description">
        Определитесь с выбором и узнайте больше об интересующих вас<br/>
        образовательных программах региона
      </div>

      <CardsFilter :filter-type="$route.meta.type" :see-more="true"/>
    </section>

    <section class="page__section">
      <h2 class="title">
        Журнал
      </h2>

      <div class="page__description">
        Узнавайте актуальные новости и читайте статьи<br/> об образовании и карьере в регионе
      </div>

      <JournalCatalog :filter-type="$route.meta.type"/>
    </section>
  </div>
</template>

<script>
export default {
  name: 'TypeCatalogView',

  methods: {
    pageType: function(current) {
      switch (current) {
        case 'for_children':
          return 'Детям';
        case 'for_entrant':
          return 'Абитуриентам';
        case 'for_adult':
          return 'Взрослым';
        default:
          return '';
      }
    },
  },
}
</script>
