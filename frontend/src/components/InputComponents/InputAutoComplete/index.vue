<template>
  <div class="form-auto-complete">
    <TagsCloud
            :tags="skills"
            :class="''"
            @updateTags="skills.push($event)"
            placeholder="Введите навыки, например, Microsoft Office"
        />
    <Select
      :array="skills"
      :class="''"
      :pre-selected="'111'"
      placeholder="Год выпуска"
      @select="skills = $event"
    />
  </div>
</template>

<script>
export default {
  name: 'InputAutoComplete',
  props: {},
  data() {
    return {
      skills: [{id: 1, name: 1}],
    };
  },
  
  methods: {},
};
</script>

<style lang="scss" scoped>
::v-deep {
}
</style>
