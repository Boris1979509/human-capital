<template>
  <div class="rating">
    <div class="rating__personal" style="margin-bottom: 12px;" v-if="$loginStatus">
      <div class="rating__description">
        <slot>Ваша оценка</slot>
      </div>
      <star-rating
          v-model="userRating"
          :star-size="20"
          :padding="1"
          inline
          rounded-corners
          active-color="#E9A35D"
          border-color="#E9A35D"
          :border-width="3"
          inactive-color="#FFF"
          text-class="rating__number"
          :fixed-points="1"
          @rating-selected="sendRating"
      />
    </div>
    <div class="info-list">
      <div class="row">
        <div class="col-50" v-if="rating.employer">
          <div class="info-list__item">
            <div class="info-list__item-number">
              {{ rating.employer }}
            </div>

            <div class="info-list__item-description">
              рейтинг по оценкам работодателей
            </div>
          </div>
        </div>
        <div class="col-50" v-if="rating.personal">
          <div class="info-list__item">
            <div class="info-list__item-number">
              {{ rating.personal }}
            </div>

            <div class="info-list__item-description">
              рейтинг по оценкам студентов
            </div>
          </div>
        </div>
        <div class="col-50" v-if="rating.institution">
          <div class="info-list__item">
            <div class="info-list__item-number">
              {{ rating.institution }}
            </div>

            <div class="info-list__item-description">
              рейтинг по оценкам образовательных организаций
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import StarRating from 'vue-star-rating';

export default {
  name: 'Rating',
  components: {StarRating},
  props: {
    rating: Object,
    rating_user: Number,
    rateableType: String,
    rateableId: Number,
  },
  data() {
    return {
      initialRating: {},
      userRating: null,
    };
  },
  mounted() {
    this.initialRating = Object.assign({}, this.rating);
    this.userRating = this.rating_user;
  },
  methods: {
    sendRating(rating) {
      this.$http.post(
          `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/${this.rateableType}/${this.rateableId}/rating`,
          {rating: rating},
      ).then(() => {
        this.userRating = rating;
      });
    },
  },
};
</script>

<style scoped>
.info-list {
  margin-top: 24px !important;
}

.info-list__item-number {
  font-weight: 800;
  font-size: 16px;
  line-height: 20px;
  color: #D06E0B;
}
</style>