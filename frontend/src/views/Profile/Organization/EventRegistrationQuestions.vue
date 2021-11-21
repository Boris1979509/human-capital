<template>
  <div>
    <div class="label">При необходимости добавьте в регистрационную форму вопросы</div>
    <div class="add-question">
      <TextInput
          class="invert"
          style="width: 90%"
          :textarea="true"
          placeholder="Текст вопроса"
          v-model="newQuestionText"
      />
      <Button @click.native="addQuestion" class="btn--light add-question-btn">
        <Icon xlink="plus"
              viewport="0 0 16 16"/>
      </Button>
    </div>
    <div class="questions">
      <div class="question" v-for="(question,i) in value" :key="i">
        <div class="text">{{ question }}</div>
        <div class="delete-btn">
          <div @click="deleteQuestion(i)" class="remove_speaker">
            <Icon xlink="delete" viewport="0 0 16 16"/>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'EventRegistrationQuestions',
  props: {
    value: Array,
  },
  data() {
    return {
      newQuestionText: '',
    };
  },
  methods: {
    addQuestion() {
      if (this.value && this.value.length) {
        this.$emit('input', [...this.value, this.newQuestionText]);
      } else {
        this.$emit('input', [this.newQuestionText]);
      }

      this.newQuestionText = '';
    },
    deleteQuestion(index) {
      let questions = this.value;
      questions.splice(index, 1);
      this.$emit('input', questions);
    },
  },
};
</script>

<style scoped lang="scss">
.label {
  font-size: 16px;
  line-height: 20px;
  margin-bottom: 16px;
  opacity: 0.48;
}

.add-question {
  display: flex;
  width: 100%;
}

.add-question-btn {
  width: 40px;
  height: 40px;
  margin-left: 8px;
}

svg {
  margin: 0 !important;
}

.questions {

}

.question {
  display: flex;
  margin-bottom: 6px;

  .text {
    width: 80%;
    margin-right: 6px;
  }
}

.delete-btn {
  width: 14px;
  cursor: pointer;
}
</style>