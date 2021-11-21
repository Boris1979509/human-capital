<template>
  <div class="popular-programs">
    <div class="programs-filter">
      <div class="row">
        <div class="col-41">
          <Range :max="max_cost ? max_cost.MAX_PRICE : 1000000"
                 :min="max_cost ? max_cost.MIN_PRICE : 0"
                 :step="1"
                 :value="filter.range"
                 label="Стоимость до"
                 @change="filter.range = $event"
                 @update="getResultOnChange($event)"
          />
        </div>

        <div class="col-33" v-if="!institutionId">
          <AutocompleteSearch :array="institution"
                              id="institution"
                              placeholder="Учебное заведение"
                              v-model="search"
                              :isLabel="false"
                              :required="true"
                              @select="filter.inst_id = $event.id, search = $event.name, getResultOnChange()"/>
        </div>

        <div class="col-25">
          <Select :array="inst_form"
                  :pre-selected="filter.inst_form"
                  placeholder="Форма обучения"
                  @select="filter.inst_form = $event, getResultOnChange()"/>
        </div>
      </div>
    </div>

    <div class="popular-programs__cards" ref="cards">
      <div class="row" style="min-height: 330px">
        <div class="col-33" v-for="card in filteredPrograms" :key="card.id">
          <PriceCard :id="card.id"
                     :image="card.institution.avatar ? card.institution.avatar.url : ''"
                     :title="card.name"
                     :desc="card.inst_program ? card.inst_program.name : ''"
                     :price="findMaxCost(card.learning_options)"
                     :type="(card.learning_options 
                            && card.learning_options[0] 
                            && card.learning_options[0].edu_form
                            && card.learning_options[0].edu_form.name) || ''"
                     :is-published="card.is_published"
                     :is-edit="false"
          />
        </div>

        <div class="col-100">
          <NothingFound text="Ни одна образовательная программа не удовлетворяет условиям фильтров"
                        v-if="filteredPrograms.length===0" />
        </div>
      </div>
    </div>

    <Button @click.native="showMore" v-if="seeMore && isShowMoreAvailable" class="btn--light">
      Показать ещё
    </Button>
  </div>
</template>

<script>
export default {
  name: 'CardsFilter',

  props: {
    filterType: {
      type: String,
    },
    seeMore: {
      type: Boolean,
      default: () => false,
    },
    institutionId: {
      type: [Number, String],
    },
    routeTransfer: {
      type: [Object, Array],
    },
  },

  watch: {
    search(val) {
      this.getAutoComplete(val);
    },
    filteredPrograms(val) {
      if (val.length === 6) {
        this.isShowMoreAvailable = true;
      } else {
        this.isShowMoreAvailable = false;
      }
    },
  },

  mounted() {
    this.filter.range = this.max_cost?.MAX_PRICE || 1000000;
    this.isShowMoreAvailable = false;
    this.isShowNothingFound = false;

    this.getAutoComplete();

    if (typeof this.filterType === 'undefined') {
      this.$nextTick(function() {
        window.addEventListener('scroll', this.onScroll);
        if (this.routeTransfer && Object.keys(this.routeTransfer).length !== 0 && this.routeTransfer.constructor === Object) {
          this.filter.inst_id = this.routeTransfer.institution_id;
          this.filter.inst_form = this.routeTransfer.edu_form;
          this.filter.range = this.routeTransfer.max_cost;
          this.getNextOnScroll(true, this.routeTransfer);
        } else {
          this.getNextOnScroll(true);
        }
      });
    } else {
      window.removeEventListener('scroll', this.onScroll);
      this.getNextOnScroll(true);
    }
  },

  beforeDestroy() {
    window.removeEventListener('scroll', this.onScroll);
  },

  computed: {
    inst_form: function() {
      return this.$dictionaries.inst_form;
    },
    max_cost: function() {
      return this.$maxProgramCost;
    },
    filteredPrograms: function() {
      return this.$filteredPrograms;
    },
    filteredProgramsMeta: function() {
      return this.$filteredProgramsMeta;
    },
  },

  data: function() {
    return {
      filter: {
        range: 1000000,
        inst_id: null,
        inst_form: null,
      },

      search: '',
      institution: [],

      queriedCardsAPI: false,
      pageCounter: 1,
      cardsPerRequest: 6,
      pageLimit: true,

      isShowMoreAvailable: false,
      isShowNothingFound: false,
    }
  },

  methods: {
    getAutoComplete: function(name) {
      const url = name ? `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions/autocomplete/?name=${name}`
          : `${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/institutions/autocomplete`;

      this.$http.get(url)
          .then((resolve) => {
            this.institution = resolve.data.data;

            if (this.routeTransfer && Object.keys(this.routeTransfer).length !== 0 && this.routeTransfer.constructor === Object && this.routeTransfer.institution_id !== null) {
              this.search = this.institution.find(item => item.id == this.routeTransfer.institution_id).full_name;
            }

            if (typeof name !== 'undefined' && name.length === 0) {
              this.filter.inst_id = null;
              this.getResultOnChange();
            }
          });
    },
    getResultOnChange: function(range) {
      this.isShowNothingFound = false;

      this.$store.dispatch('GET_FILTERED_PROGRAMS_LIST_FROM_SERVER', {
        clear: true,
        params: {
          order_by: '-published_at',
          limit: this.cardsPerRequest,
          page: 1,
          filter: this.filterType,
          max_cost: range ? range : this.filter.range,
          institution_id: this.institutionId ? this.institutionId : this.filter.inst_id,
          edu_form: this.filter.inst_form,
        },
      }).then(() => {
        if (range) {
          this.filter.range = range;
        } else {
          this.filter.range = 100000;
        }

        if (this.filteredPrograms.length === 0) this.isShowNothingFound = true;

        if (this.filteredPrograms.length <= 5) {
          this.pageLimit = null;
        } else {
          this.pageLimit = this.filteredProgramsMeta?.to;
        }

        this.pageCounter = 2;
        this.queriedCardsAPI = false;
      });
    },
    getNextOnScroll: function(clear, params) {
      if (!this.queriedCardsAPI) {
        this.queriedCardsAPI = true;
        this.isShowNothingFound = false;

        if (this.pageLimit !== null) {
          this.$store.dispatch('GET_FILTERED_PROGRAMS_LIST_FROM_SERVER', {
            clear: clear,
            params: params ? params : {
              order_by: '-published_at',
              limit: this.cardsPerRequest,
              page: this.pageCounter,
              filter: this.filterType,
              max_cost: this.filter.range,
              institution_id: this.institutionId ? this.institutionId : this.filter.inst_id,
              edu_form: this.filter.inst_form,
            },
          }).then(() => {
            if (this.filteredPrograms.length === 0) this.isShowNothingFound = true;
            this.pageCounter++;
            this.pageLimit = this.filteredProgramsMeta?.to;
            this.queriedCardsAPI = false;
          });
        }
      }
    },
    onScroll: function() {
      const cards = this.$refs['cards'];

      if (cards) {
        var cardsBottom = cards.getBoundingClientRect().bottom;
        var innerHeight = window.innerHeight;

        if ((cardsBottom - innerHeight) < -50) {
          this.getNextOnScroll();
        }
      }
    },
    findMaxCost: function(array) {
      return Math.max.apply(Math, array.map(max => max.cost));
    },
    showMore: function() {
      this.$router.push({
        name: 'EduProgramsFilterView',
        params: {
          order_by: '-published_at',
          filter: this.filterType,
          max_cost: this.filter.range,
          institution_id: this.institutionId ? this.institutionId : this.filter.inst_id,
          edu_form: this.filter.inst_form,
        },
      });
    },
  },
}
</script>
