<template>
  <div class="select" :style="`margin-bottom: ${margin}px`" :class="disabled ? 'select--disabled' : ''">
    <div class="select__container">
      <div
          v-click-outside="closeList"
          class="select__selector"
          v-on:click="toggleList"
      >
        <div class="select__selected" :class="preSelected || selected ? 'select__selected--active' : ''">
          {{ preSelected ? fillSelected(preSelected) : selected }}
        </div>

        <span class="select__placeholder" :class="preSelected || selected ? 'select__placeholder--active' : ''">
          {{ placeholder }}
        </span>

        <Icon class="select__icon"
              :rotate="showList ? 0 : 180"
              xlink="dropdown"
              viewport="0 0 16 6"
        />
      </div>

      <div v-if="showList" class="select__list">
        <div v-for="item in optionsList"
             :key="item.id"
             v-on:click="select(item.id)"
             class="select__item"
             :class="item.name === selected || item.id === preSelected ? 'select__item--chosen' : ''"
        >
          {{ item.name }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'Select',

  props: {
    placeholder: {
      type: String,
    },
    array: {
      type: [Array, Object],
    },
    margin: {
      type: Number,
      default: () => 20,
    },
    preSelected: {
      type: [String, Number],
    },
    disabled: {
      type: Boolean,
      default: () => false,
    },
  },

  computed: {
    optionsList() {
      let options = [...this.array];
      options.unshift({id: null, name: 'Не выбрано'});
      return options;
    },
  },

  data: function() {
    return {
      showList: false,
      selected: this.preSelected ? this.preSelected : 'Не выбрано',
    };
  },

  watch: {
    disabled() {
      this.selected = null
    }
  },

  methods: {
    closeList: function() {
      this.showList = false;
    },

    toggleList: function() {
      this.showList = !this.showList;
    },

    fillSelected: function(id) {
      let preselectedOption = this.optionsList.find(current => current.id === parseInt(id));
      if (preselectedOption) {
        return preselectedOption.name;
      }
      return {id: null, name: 'Не выбрано'};
    },

    select(id) {
      let selectedOption = this.optionsList.find(current => current.id === id);
      if (!selectedOption) {
        this.selected = {id: null, name: 'Не выбрано'};
      } else {
        this.selected = selectedOption.name;
      }
      this.$emit('select', id);
    },
  },
};
</script>
