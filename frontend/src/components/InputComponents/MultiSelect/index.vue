<template>
  <div class="select" :style="`margin-bottom: ${margin}px`" :class="disabled ? 'select--disabled' : ''">
    <div class="select__container" v-click-outside="closeList">
      <div class="select__selector" v-on:click="toggleList">
        <div class="select__selected select__selected--active">
          Выбрано: {{ selected.length }}
        </div>

        <span class="select__placeholder select__placeholder--active">
          {{ placeholder }}
        </span>

        <Icon class="select__icon"
              :rotate="showList ? 0 : 180"
              xlink="dropdown"
              viewport="0 0 16 6"
        />
      </div>

      <div v-if="showList" class="select__list select__list--checkbox">
        <Checkbox
            :key="'all'"
            id="all"
            :checked="is_checked"
            :indeterminate="isPartial"
            :label="allLabel"
            @change="checkEvery($event)"
            :margin="32"
            class="select__checkbox"
        />

        <Checkbox
            v-for="item in list"
            :key="item.id"
            :id="item.id"
            :checked="item.is_checked"
            :label="item.name"
            @change="item.is_checked = $event; checkOne(item);"
            :margin="24"
            class="select__checkbox"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MultiSelect',

  props: {
    placeholder: {
      type: String,
    },
    array: {
      type: [Array, Object],
      default: () => [],
    },
    margin: {
      type: Number,
      default: () => 20,
    },
    preSelected: {
      type: Array,
      default: () => [],
    },
    disabled: {
      type: Boolean,
      default: () => false,
    },
    allLabel: {
      type: String,
      default: () => 'Все',
    },
  },

  created() {
    this.init();
  },

  data: function() {
    return {
      showList: false,

      list: this.array,

      is_checked: false,
      isPartial: false,

      selected: [],
    };
  },

  watch: {
    array(val) {
      this.list = val;
      this.init();
    },
    preSelected() {
      this.init();
    }
  },

  methods: {
    init() {
      this.list.forEach((element, index) => {
        this.list[index].is_checked = false;
      });

      this.selected = this.preSelected;
      if (this.preSelected.length > 0) {
        for (let i = 0; i < this.preSelected.length; i++) {
          let item = this.list.find(item => item.id === this.preSelected[i]);
          if(item) {
            item.is_checked = true;
          }
        }
      }

      if (this.preSelected.length < this.list.length && this.preSelected.length !== 0) {
        this.isPartial = true;
      } else if (this.preSelected.length === this.list.length) {
        this.is_checked = true;
      } else {
        this.is_checked = false;
      }
    },
    checkEvery(e) {
      this.isPartial = false;
      this.selected = [];

      if (e) {
        this.is_checked = true;
        this.list.forEach(item => {
          item.is_checked = true;
          this.selected.push(item.id);
        });
      } else {
        this.is_checked = false;
        this.list.forEach(item => {
          item.is_checked = false;
        });
        this.selected = [];
      }

      this.$emit('select', this.selected);
    },

    checkOne(item) {
      if (this.list.some(item => item.is_checked) && !this.list.every(item => item.is_checked)) {
        this.is_checked = false;
        this.isPartial = true;
      } else if (this.list.every(item => item.is_checked)) {
        this.is_checked = true;
        this.isPartial = false;
      } else if (!this.list.every(item => item.is_checked)) {
        this.is_checked = false;
        this.isPartial = false;
      }

      if (item.is_checked) {
        this.selected.push(item.id);
      } else {
        const index = this.selected.indexOf(item.id);
        if (index > -1) {
          this.selected.splice(index, 1);
        }
      }

      this.$emit('select', this.selected);
    },

    closeList: function() {
      this.showList = false;
    },

    toggleList: function() {
      this.showList = !this.showList;
    },
  },
};
</script>
