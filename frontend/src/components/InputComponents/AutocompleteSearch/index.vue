<template>
  <div class="input-group autocomplete invert"
       :style="`margin-bottom: ${margin}px`"
       v-click-outside="closeList">
    <label :for="id" v-if="isLabel">
      {{ label }}
    </label>

    <input type="text"
           :id="id"
           v-model="inputValue"
           :disabled="disabled"
           autocomplete="off"
           :required="required"
           @focus="showList = true"
    />

    <span class="input-group__placeholder" :class="disabled && inputValue ? 'hasValue' : ''">
      {{ placeholder }}
    </span>

    <Button @click.native="clearInput" v-if="inputValue.length > 0" class="clear-btn">
      <Icon xlink="close_big"
            viewport="0 0 16 16"
      />
    </Button>

    <div v-if="showList" class="select__list">
      <div v-for="item in optionsList"
           :key="item.id"
           v-on:click="select(item.id, type === 'employer' ? item.name : item.full_name)"
           class="select__item"
      >
        {{ type === 'employer' ? item.name : item.full_name }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'AutocompleteSearch',

  props: {
    id: {
      type: String
    },
    type: {
      type: String,
    },
    array: {
      type: [Array, Object],
    },
    isLabel: {
      type: Boolean,
      default: () => true,
    },
    label: {
      type: String
    },
    placeholder: {
      type: String
    },
    margin: {
      type: Number,
      default: () => 20,
    },
    required: {
      type: Boolean,
      default: () => true
    },
    disabled: {
      type: Boolean,
    },
    value: {}
  },

  data: function() {
    return {
      showList: false,
      selected: {},
    }
  },

  computed: {
    optionsList() {
      return this.array;
    },

    inputValue: {
      get() {
        return this.value;
      },
      set(string) {
        this.$emit('input', string);
      },
    },
  },

  methods: {
    closeList: function() {
      this.showList = false;
    },

    select(id, name) {
      this.selected = this.optionsList.find(current => current.id === id);
      this.showList = false;
      this.$emit('select', {id: id, name: name});
    },

    clearInput: function() {
      this.selected = {};
      this.showList = false;
      this.$emit('input', '');
    },
  },
}
</script>
