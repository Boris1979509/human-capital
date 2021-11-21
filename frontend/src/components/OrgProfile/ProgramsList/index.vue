<template>
  <div class="profile__container">
    <div class="row">
      <div class="col-50">
        <router-link to="/profile/org/programs/add" class="price-card price-card--add">
          <div class="price-card__center-container">
            <Icon xlink="plus"
                  viewport="0 0 16 16"/>
            Добавить
          </div>
        </router-link>
      </div>

      <div class="col-50" v-for="(card, key) in programs" :key="key">
        <PriceCard :id="card.id"
                   :institution="org_id"
                   :image="org_avatar"
                   :title="card.name"
                   :desc="whatTypeIs(card.type_id)"
                   :price="card.learning_options ? card.learning_options[0].cost : ''"
                   :type="card.direction_of_study"
                   :is-published="card.is_published"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProgramsList',

  computed: {
    orgInfo: function() {
      return this.$organization;
    },
    programs: function() {
      return this.$programs;
    },
    inst_program: function() {
      return this.$dictionaries.inst_program;
    },
  },

  created() {
    if (this.orgInfo.length !== 0) {
      this.org_id = this.orgInfo[0].id;
      this.org_avatar = this.orgInfo[0].avatar?.url;
    }
  },

  data: function() {
    return {
      org_id: '',
      org_avatar: null,
    }
  },

  methods: {
    whatTypeIs: function(typeId) {
      const name = this.inst_program.find(item => item.id === typeId).name;
      return name;
    },
  },
}
</script>
