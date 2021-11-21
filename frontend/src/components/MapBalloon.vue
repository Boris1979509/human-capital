<template>
  <div class="balloon-card">
    <div class="image" v-if="image">
      <img :src="image" alt="">
    </div>
    <div class="info">
      <div class="title">
        {{ title }}
      </div>
      <div class="subtitle">
        {{ subtitle }}
      </div>
      <div class="description">
        {{ description }}
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'MapBalloon',
  props: {
    image: String,
    title: String,
    subtitle: String,
    description: String,
  },
};
</script>

<style lang="scss">
.balloon-card {
  border-radius: 16px !important;

  .image {
    height: 122px;

    img {
      max-height: 122px;
    }
  }

  .info {
    padding: 16px 12px;

    .title {
      font-weight: 800;
      font-size: 16px;
      line-height: 20px;
      color: #04153E;
      margin-bottom: 4px;
    }

    .subtitle {
      font-weight: normal;
      font-size: 13px;
      line-height: 16px;
      color: #04153E;
      opacity: 0.48;
      margin-bottom: 12px;
    }

    .description {
      font-style: normal;
      font-weight: normal;
      font-size: 13px;
      line-height: 16px;
      color: #04153E;
    }
  }
}

.ymaps-2-1-78-balloon__content {
  padding: 0 !important;
  margin: 0 !important;
  border-radius: 16px !important;
}

.ymaps-2-1-78-balloon__layout {
  border-radius: 16px !important;
}

.ymaps-2-1-78-balloon {
  border-radius: 16px !important;
  box-shadow: none;
}
</style>