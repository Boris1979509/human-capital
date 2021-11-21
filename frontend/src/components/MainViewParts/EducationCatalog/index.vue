<template>
  <div class="education-catalog">
    <div class="row">
      <div class="col-25" v-for="type in summary" :key="type.id">
        <div class="education-catalog__item" :class="colors[$getRandomColor(colors.length)]">
          <div class="education-catalog__item-title"
               @click="$router.push(getRoute(type))">
            {{ type.title }}
          </div>

          <div class="education-catalog__list">
            <div v-for="item in type.items" :key="item.id">
              <div class="education-catalog__list-item">
                <div class="education-catalog__list-item-name"
                     @click="getLinkForItem(item)">
                  {{ item.dictionary_id.name }}
                </div>

                <div class="education-catalog__list-item-counter">
                  {{ item.count }}
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EducationCatalog',

  data: function() {
    return {
      summary: [],
      colors: ['bg--violet', 'bg--orange', 'bg--blue', 'bg--green', 'bg--red'],
      programsFetched: false,
    };
  },

  mounted() {
    this.getInfo();
  },

  methods: {
    getInfo: function() {
      this.summary = [];
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/ui/panels?type=for_main`).then(data => {
        this.summary = data.data.data;
      });
    },
    getRoute(type) {
      return {name: 'MainViewOrganizations', params: {type: this.getEduTypesIds(type)}};
    },
    getEduTypesIds(type) {
      return type.items.reduce((result, item) => {
        if (item.dictionary_id.type.id === 10) {
          result.push(item.dictionary_id);
        }
        return result;
      }, []);
    },
    getLinkForItem(item) {
      if (item.dictionary_id.type.id === 10) {
        this.$router.push({name: 'MainViewOrganizations', params: {type: [item.dictionary_id]}});
      }
    },
  },
};
</script>
