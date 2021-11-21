<template>
  <div class="compilation__wrapper">
    <breadcrumbs class="mb-26" />
    <div class="compilation__title">
      <h1>
        Подборки для вас
      </h1>
      <p>
        Мы нашли те материалы нашего портала, которые могут вас заинтересовать и
        собрали их в одном месте
      </p>
    </div>
    <div class="compilation__list">
      <div
        class="compilation__item"
        v-for="(item, idx) in compilations"
        :key="idx"
        @click="
          $router.push({
            'name': 'MainViewCompilationsItem',
            'params': {
              'id': item.id,
            },
          })
        "
        :style="`box-shadow: 4px 0px 0px 0px ${getRandomColor()}`"
      >
        <div class="compilation__item__title">
          {{ item.title }}
        </div>
        <div class="compilation__item__description">
          {{ item.annotation }}
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MainViewCompilations',
  data() {
    return {
      compilations: [],
      randomColors: ['#F8D6B5', '#C0D6F6', '#F7CFD5', '#C7E6A8'],
      // prevColor: randomColors[0],
    };
  },
  created() {
    this.$http
      .get(`${process.env.VUE_APP_DEFAULT_DEVELOP_HOST}api/selections/`, {
        params: {
          order_by: '-published_at',
        },
      })
      .then((response) => {
        if (response.status === 200) console.log(response);
        else throw Error('error occured while compilations getting');
        const {
          data: { data: data },
        } = response;
        this.compilations = data;
        // this.compilations.fill(data[0]);
      })
      .catch((err) => {
        console.log(err);
      });
  },
  methods: {
    getRandomColor() {
      return this.randomColors[Math.round(Math.random() * 3)];
    },
  },
};
</script>

<style lang="scss" scoped>
.compilation__wrapper {
  width: 100%;
  .mb-26 {
    margin-bottom: 26px;
  }
  .compilation {
    &__title {
      max-height: max-content;
      margin-bottom: 40px;
      h1 {
        font-family: Golos;
        font-size: 40px;
        font-style: normal;
        font-weight: 800;
        line-height: 44px;
        margin-bottom: 16px;
      }
      p {
        font-family: Golos;
        font-size: 16px;
        font-style: normal;
        font-weight: 400;
        line-height: 24px;
        opacity: 0.72;
      }
    }
    &__list {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      grid-gap: 32px;
      width: 100%;
      .compilation__item {
        max-width: 294px;
        padding: 20px;
        border-radius: 16px;
        background: linear-gradient(53.01deg, #e6ebf5 0%, #f5f8fc 100%);
        cursor: pointer;
        // box-shadow: 4px 0px 0px 0px #f00;
        border-right: 4px solid #e6ebf5;
        &__title {
          font-family: Golos;
          font-size: 20px;
          font-style: normal;
          font-weight: 800;
          line-height: 24px;
          margin-bottom: 12px;
        }
        &__description {
          font-family: Golos;
          font-size: 16px;
          font-style: normal;
          font-weight: 400;
          line-height: 20px;
          opacity: 0.72;
        }
      }
    }
  }
}
</style>
