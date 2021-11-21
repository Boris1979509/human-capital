<template>
  <div class="MainViewJournal">
    <breadcrumbs />
    <router-view />
  </div>
</template>

<script>
export default {
  name: 'MainViewJournal',
  data() {
    return {};
  },
  
  methods: {
    setItemRoute(item) {
      if (item)
        this.$router.push({
          name: 'MainViewJournalNews',
          params: {
            id: item.id,
          },
        });
    },
    setSelectedFilterItem(array, index) {
      if (array.length) {
        array.forEach((element) => {
          if (element.selected) element.selected = false;
        });
      }
      if (array[index]) array[index].selected = true;
    },
  },
};
</script>

<style lang="scss" scoped>
.MainViewJournal {
  width: 100%;
  
}
</style>
