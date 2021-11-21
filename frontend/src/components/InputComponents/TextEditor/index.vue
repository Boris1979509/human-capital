<template>
  <div class="text-editor__wrapper">
    <editor-controls :editor="editor"/>
    <editor-content :editor="editor" class="text-editor__input-block"/>
  </div>
</template>

<script>
import {Editor, EditorContent} from '@tiptap/vue-2';
import {defaultExtensions} from '@tiptap/starter-kit';
import EditorControls from './EditorControls';

export default {
  components: {
    EditorControls,
    EditorContent,
  },

  props: {
    value: {
      type: String,
      default: '',
    },
  },

  data() {
    return {
      editor: null,
      headings: [
        {name: 'h1', id: 1},
        {name: 'h2', id: 2},
        {name: 'h3', id: 3},
        {name: 'h4', id: 4},
        {name: 'h5', id: 5},
        {name: 'h6', id: 6},
      ],
    };
  },

  watch: {
    value(value) {
      const isSame = this.editor.getHTML() === value;
      if (isSame) {
        return;
      }
      this.editor.commands.setContent(this.value, false);
    },
  },

  mounted() {
    this.editor = new Editor({
      extensions: [...defaultExtensions()],
      content: this.value,
      onUpdate: () => {
        this.$emit('input', this.editor.getHTML());
      },
    });
  },

  beforeDestroy() {
    this.editor.destroy();
  },
};
</script>

<style lang="scss">
/* Basic editor styles */
.text-editor {

  &__input-block {
    padding: 24px 16px;
    border-radius: 8px;
    background: var(--main-color-trans-light);
    position: relative;

    &:before {
      content: 'Введите текст';
      font-size: 13px;
      opacity: 0.32;
      position: absolute;
      top: 4px;
      left: 16px;
    }
  }
}

.ProseMirror {
  min-height: 150px;
  outline: none;

  > * + * {
    margin-top: 0.75em;
  }

  em {
    font-style: italic;
  }

  strong {
    font-weight: bold;
    font-size: inherit;

    em {
      font-style: italic;
      font-weight: bold;
    }
  }

  ul,
  ol {
    padding: 0 1rem;
  }

  ul {
    list-style: disc;
  }

  ol {
    list-style: decimal;
  }

  h1,
  h2,
  h3,
  h4,
  h5,
  h6 {
    line-height: 1.1;
  }

  h1 {
    font-weight: 800;
    font-size: 40px;
    line-height: 44px;
  }

  h2 {
    font-weight: 800;
    font-size: 32px;
    line-height: 36px;
  }

  h3 {
    font-weight: 800;
    font-size: 28px;
    line-height: 32px;
  }

  h4 {
    font-weight: 700;
    font-size: 24px;
    line-height: 28px;
  }

  h5 {
    font-weight: 800;
    font-size: 20px;
    line-height: 24px;
  }

  h6 {
    font-weight: 800;
    font-size: 16px;
    line-height: 20px;
  }

  code {
    background-color: rgba(#616161, 0.1);
    color: #616161;
  }

  pre {
    background: #0d0d0d;
    color: #fff;
    font-family: 'JetBrainsMono', monospace;
    padding: 0.75rem 1rem;
    border-radius: 0.5rem;

    code {
      color: inherit;
      padding: 0;
      background: none;
      font-size: 0.8rem;
    }
  }

  img {
    max-width: 100%;
    height: auto;
  }

  blockquote {
    padding-left: 1rem;
    border-left: 2px solid rgba(#0d0d0d, 0.1);
  }

  hr {
    border: none;
    border-top: 2px solid rgba(#0d0d0d, 0.1);
    margin: 2rem 0;
  }
}

.error .text-editor__input-block {
  background-color: var(--error-bg);
  color: rgba(52, 4, 15, .72);
}
</style>
