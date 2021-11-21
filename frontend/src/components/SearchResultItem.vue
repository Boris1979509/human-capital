<template>
  <div class="item" @click="navigateToItem">
    <div class="type">{{ itemType }}</div>
    <div class="title" v-html="item.title"></div>
    <div class="item__content" v-line-clamp="2" v-html="item.text"></div>
  </div>
</template>

<script>
export default {
  name: 'SearchResultItem',
  props: {
    item: Object,
  },
  computed: {
    itemType() {
      switch (this.item.type) {
        case 'Новость':
        case 'Мероприятие':
        case 'Статья':
          return 'Журнал';
        case 'employer':
          return 'Работодатель';
        case 'institution':
          return 'Образовательная организация';
        case 'curriculum':
          return 'Образовательная программа';
        case 'vacancy':
          return 'Вакансия';
        default:
          return '';
      }
    },
  },
  methods: {
    navigateToItem() {
      let link = '';
      switch (this.item.type) {
        case 'Новость':
          link = `/journal/news/${this.item.id}`;
          break;
        case 'Мероприятие':
          link = `/journal/event/${this.item.id}`;
          break;
        case 'Статья':
          link = `/journal/article/${this.item.id}`;
          break;
        case 'employer':
          link = `/employers/${this.item.id}`;
          break;
        case 'institution':
          link = `/institutions/${this.item.id}`;
          break;
        case 'curriculum':
          link = `/programs/${this.item.id}`;
          break;
        case 'vacancy':
          link = `/job/vacancies/${this.item.id}`;
          break;
      }
      this.$router.push(link);
    },
  },
};
</script>

<style scoped lang="scss">
.item {
  margin-bottom: 24px;
  cursor: pointer;

  .type {
    font-weight: normal;
    font-size: 13px;
    line-height: 16px;
    color: #57A003;
  }

  .title {
    font-weight: 800;
    font-size: 20px;
    line-height: 24px;
    color: #04153E;
  }

  &__content {
    font-weight: normal;
    font-size: 16px;
    line-height: 24px;
    color: #04153E;

  }
}
</style>
<style>
.item .title em {
  font-weight: 800 !important;
  font-size: 20px !important;
  line-height: 24px !important;
  color: #3D75E4 !important;
}
</style>