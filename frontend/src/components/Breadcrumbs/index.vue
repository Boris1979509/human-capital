<template>
  <div class="breadcrumbs" :style="margin !== 0 ? `margin-bottom: ${margin}px` : ''">
    <ul class="breadcrumbs__wrapper">
      <li v-for="(breadcrumb, id) in breadcrumbList"
          :key="id"
          @click="routeTo(id)"
          class="breadcrumbs__link"
          :class="{'breadcrumbs__link--linked': !!breadcrumb.link}">
        {{ breadcrumb.name }}
      </li>
    </ul>
  </div>
</template>

<script>
export default {
  name: 'Breadcrumbs',

  props: {
    margin: {
      type: Number,
      default: () => 0,
    },
  },

  data() {
    return {
      breadcrumbList: [],
    }
  },

  mounted() {
    this.updateList();
  },

  watch: {
    '$route'() {
      this.updateList();
    }
  },

  methods: {
    routeTo(id) {
      if (this.breadcrumbList[id].link) {
        this.$router.push({
          name: this.breadcrumbList[id].link,
          params: {
            id: this.breadcrumbList[id].params?.id,
          },
        });
      }
    },
    updateList() {
      this.breadcrumbList = this.$route.meta.breadcrumb;
    }
  },
}
</script>
