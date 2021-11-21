<template>
  <div class="social">
    <div class="social__label" v-if="label">
      Поделиться в соцсетях
    </div>
    <a :href="vkLink" title="ВКонтакте" class="social__link" target="_blank">
      <Icon xlink="vk" viewport="0 0 32 32"/>
    </a>

    <!--    <a :href="instaLink" title="Инстаграм" class="social__link">-->
    <!--      <Icon xlink="instagram" viewport="0 0 32 32"/>-->
    <!--    </a>-->

    <a :href="fbLink" title="Фейсбук" class="social__link" target="_blank">
      <Icon xlink="fb" viewport="0 0 32 32"/>
    </a>
  </div>
</template>

<script>
export default {
  name: 'SocialSharing',
  props: {
    title: {String},
    description: {String},
    image: {String},
    link: {String},
    label: {
      type: Boolean,
      default: true,
    },
  },
  computed: {
    imageLink(){
      return this.image?.url;
    },
    vkLink() {
      return `//vk.com/share.php?url=${this.link}&title=${this.title}&description=${this.description}&image=${this.imageLink}&noparse=true`;
    },
    instaLink() {
      return 'https://www.instagram.com/';
    },
    fbLink() {
      return `https://www.facebook.com/sharer/sharer.php?u=${this.link}&picture=${this.image}`;
    },
  },
};
</script>

<style lang="scss" scoped>
.social {
  margin: 18px 0;

  &__label {
    color: var(--main-color-dark-trans-middle);
    font-family: Golos;
    font-style: normal;
    font-weight: normal;
    font-size: 13px;
    line-height: 16px;
    margin-bottom: 12px;
  }
}
</style>
