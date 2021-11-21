<template>
  <div class="sections_wrapper">
    <div class="section" v-if="orgInfo.length">
      <div class="section_news">
        <div class="section_label">
          Новости
        </div>
        <Slider
            :array="news"
            :ribbon-label="'Новость'"
            :ribbon-color="'#F196A5'"
            :add-btn-label="'Добавить новость'"
            :route-name="'ProfileOrgAddJournalNewsView'"
            :edit-route-name="'ProfileOrgEditJournalNewsView'"
        />
      </div>

      <div class="section_events">
        <div class="section_label">
          Мероприятия
          <span class="responses-link" @click="$router.push({name: 'ProfileOrgRegistrationsListView'})">
        <svg width="16" height="16" viewBox="0 0 16 16" fill="none" xmlns="http://www.w3.org/2000/svg">
          <path
              d="M12.4472 0.105792C11.9532 -0.141197 11.3526 0.059027 11.1056 0.553005C10.8586 1.04698 11.0588 1.64766 11.5528 1.89465C11.889 2.06277 12.312 2.474 12.6835 2.91858C12.8831 3.15747 13 3.49949 13 3.90855V14.0002H3V2.00022H8.5V3.50022C8.5 4.60479 9.39543 5.50022 10.5 5.50022H11.5C12.0523 5.50022 12.5 5.0525 12.5 4.50022C12.5 3.94793 12.0523 3.50022 11.5 3.50022H10.5V2.00022C10.5 0.89565 9.60457 0.000219044 8.5 0.000219044H3C1.89543 0.000219044 1 0.89565 1 2.00022V14.0002C1 15.1048 1.89543 16.0002 3 16.0002H13C14.1046 16.0002 15 15.1048 15 14.0002V3.90855C15 3.13305 14.7781 2.30617 14.2182 1.6361C13.8261 1.16691 13.18 0.472186 12.4472 0.105792Z"
              fill="#214EB0"/>
          <path
              d="M11.2593 8.65105C11.6187 8.23173 11.5701 7.60043 11.1508 7.24101C10.7315 6.88158 10.1002 6.93015 9.74074 7.34947L7.44352 10.0296L6.20711 8.79315C5.81658 8.40263 5.18342 8.40263 4.79289 8.79315C4.40237 9.18368 4.40237 9.81684 4.79289 10.2074L6.79289 12.2074C6.98985 12.4043 7.26004 12.5102 7.53838 12.4995C7.81672 12.4888 8.07798 12.3625 8.25926 12.1511L11.2593 8.65105Z"
              fill="#214EB0"/>
        </svg>
        Заявки
      </span>
        </div>
        <Slider
            :array="events"
            :ribbon-label="'Мероприятие'"
            :ribbon-color="'#8DC95E'"
            :add-btn-label="'Добавить мероприятие'"
            :route-name="'ProfileOrgAddJournalEventView'"
            :edit-route-name="'ProfileOrgEditJournalEventView'"
        />
      </div>
      <div class="section_articles">
        <div class="section_label">
          Статьи
        </div>

        <Slider
            :array="articles"
            :ribbon-label="'Статья'"
            :ribbon-color="'#BB9AF4'"
            :add-btn-label="'Добавить статью'"
            :route-name="'ProfileOrgAddJournalNoteView'"
            :edit-route-name="'ProfileOrgEditJournalNoteView'"
        />
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'ProfileOrgJournalListView',
  data: function() {
    return {
      journal: [],
      news: [],
      events: [],
      articles: [],
    };
  },
  computed: {
    orgInfo: function() {
      return this.$organization;
    },
  },
  watch: {
    orgInfo(val) {
      if (val.length) {
        this.getContent();
      }
    },
  },
  mounted() {
    if (this.orgInfo.length) {
      this.getContent();
    }
  },
  methods: {
    getContent() {
      // all (published and not) events fetching
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgInfo[0].id}/journal/`, {
        params: {
          type: 3,
          order_by: '-published_at',
        },
      }).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal events getting');
        const {
          data: {data: data},
        } = response;
        this.events = data;
      }).catch((err) => {
        console.log(err);
      });
      // all (published and not) articles fetching
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgInfo[0].id}/journal/`, {
        params: {
          type: 2,
          order_by: '-published_at',
        },
      }).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal articles getting');
        const {
          data: {data: data},
        } = response;
        this.articles = data;
      }).catch((err) => {
        console.log(err);
      });
      // all (published and not) news fetching
      this.$http.get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/lk/institutions/${this.orgInfo[0].id}/journal/`, {
        params: {
          type: 1,
          order_by: '-published_at',
        },
      }).then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while journal news getting');
        const {
          data: {data: data},
        } = response;
        this.news = data;
      }).catch((err) => {
        console.log(err);
      });
    },
  },
};
</script>
<style lang="scss" scoped>
.sections_wrapper {
  .section {
    &_label {
      font-family: Golos;
      font-style: normal;
      font-weight: 800;
      font-size: 24px;
      margin-bottom: 24px;
      line-height: 28px;
    }

    &_news,
    &_events,
    &_articles {
      margin-bottom: 38px;
    }
  }
}

.responses-link {
  margin-left: 24px;
  font-weight: 500;
  font-size: 16px;
  line-height: 20px;
  text-align: right;
  color: #214EB0;
  cursor: pointer;

  svg {
    margin-bottom: -2px;
  }
}
</style>
