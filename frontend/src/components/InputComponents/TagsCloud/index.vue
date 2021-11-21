<template>
  <div class="tags-cloud" :style="`margin-bottom: ${margin}px`">
    <div class="tags-cloud__container" style="margin-bottom: 20px">
      <div class="input-group invert">
        <input type="text"
               :id="id"
               v-model.trim="value"
               autocomplete="new-password"
               :required="required"
               :disabled="disabled"
               @keypress.enter="createTag"
               @keydown.esc="nullifyPrompt"
               @keydown="iterateOverPrompt"
               @input="fetchPrompt"
        />

        <span class="input-group__placeholder" :class="disabled && value ? 'hasValue' : ''">
        {{ placeholder }}
        </span>

        <div v-if="prompt.length" v-click-outside="nullifyPrompt" class="select__list">
          <div v-for="item in prompt"
              :key="item.id"
              v-on:click="selectPrompt(item.word)"
              class="select__item"
              :class="[{'active': item.active}]"
          >
            {{ item.word }}
          </div>
        </div>
      </div>

      <Button @click.native="createTag" class="btn--light">
        <Icon xlink="plus"
              viewport="0 0 16 16"/>
      </Button>
    </div>

    <div class="tags-cloud__list">
      <div class="tag" v-for="(tag, key) in tags" :key="key">
        {{ tag }}
        <Button @click.native="deleteTag(key)" class="tag__delete">
          <Icon xlink="close"
                viewport="0 0 8 8"
          />
        </Button>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'TagsCloud',

  props: {
    id: {
      type: String,
    },
    placeholder: {
      type: String,
    },
    tags: {
      type: Array,
      default: () => ([]),
    },
    margin: {
      type: Number,
      default: () => 20,
    },
    required: {
      type: Boolean,
      default: () => false,
    },
    disabled: {
      type: Boolean,
    },
    autoComplete: {
      type: Boolean,
      default: false,
    },
    type: {
      type: Number,
      default: 1,
    },
  },

  data: function() {
    return {
      value: null,
      prompt: [],
      currentPromptItem: -1,
    };
  },

  methods: {
    selectPrompt: function(item) {
      this.value = item;
    },
    fetchPrompt: async function(e) {
      if(!this.autoComplete) return;
      if(!e.data) {
        this.nullifyPrompt();
        return;
      }
      if(this.value.length < 3) return;
      const {data: { data }} = await this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/ui/autocomplete?type=${this.type}&q=${this.value}`);
      this.prompt = (data && data.length && data.map((item) => {item.active = false; return item}));
    },
    iterateOverPrompt: function(e) {
      if(!this.prompt.length) return;
      if(e.keyCode === 38 || e.keyCode === 40) {
        if(e.keyCode === 38) this.currentPromptItem--;
        if(e.keyCode === 40) this.currentPromptItem++;
        this.prompt.forEach(item => item.active = false);
        if(this.currentPromptItem < 0) this.currentPromptItem = this.prompt.length - 1;
        if(this.currentPromptItem > this.prompt.length - 1) this.currentPromptItem = 0;  
        this.prompt[this.currentPromptItem].active = true;
        this.selectPrompt(this.prompt[this.currentPromptItem].word);
      }
    },
    nullifyPrompt: function() {
      this.prompt = [];
      this.currentPromptItem = -1; 
    },
    createTag: function() {
      if (this.value.length) {
        this.$emit('updateTags', this.value.replace(/\s+/g, ' '));
        this.value = '';
        this.nullifyPrompt();
      }
    },

    deleteTag: function(key) {
      this.tags.splice(key, 1);
    },
  },
};
</script>

<style scoped lang="scss">
.error {
  input {
    color: rgba(52, 4, 15, .72);
    background-color: var(--error-bg) !important;
  }
}
</style>
