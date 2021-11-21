<template>
  <section class="page__section" v-if="employer.contacts">
    <h2 class="employer-page__title title">
      Контакты
    </h2>

    <div class="row">
      <div class="col-33">
        <div class="contact" v-for="(item, key) in employer.contacts" :key="key">
          <h5 class="contact__title title">
            {{ item.name }}
          </h5>

          <div class="contact__item">
            <Icon xlink="location"
                  viewport="0 0 16 16"/>
            {{ employer.address }}
          </div>

          <div class="contact__item" v-for="phone in item.phones" :key="phone">
            <Icon xlink="phone" viewport="0 0 16 16"/>

            <a :href="`tel:${phone}`">
              {{ phone }}
            </a>
          </div>

          <div class="contact__item" v-for="email in item.emails" :key="email">
            <Icon xlink="email" viewport="0 0 16 16"/>

            <a :href="`mailto:${email}`">
              {{ email }}
            </a>
          </div>
        </div>

        <div class="contact__website" v-if="employer.website">
          <div class="contact__label" style="margin-bottom: 5px;">
            Официальный сайт
          </div>

          <a :href="employer.website" title="Официальный сайт" class="contact__link">
            {{ employer.website }}
          </a>
        </div>

        <div class="social" style="margin-top: 24px;" v-if="employer.link_vk || employer.link_fb">
          <div class="contact__label">
            Мы в соцсетях
          </div>

          <a :href="employer.link_vk" v-if="employer.link_vk" title="Вконтакте" class="social__link">
            <Icon xlink="vk" viewport="0 0 32 32"/>
          </a>

          <a :href="employer.link_fb" v-if="employer.link_fb" title="Фейсбук" class="social__link">
            <Icon xlink="fb" viewport="0 0 32 32"/>
          </a>
        </div>
      </div>

      <div class="col-66">
        <div class="map">
          <Map :markers="[{
            coords: employer.coords,
            title: employer.name,
            subtitle: employer.branch.name,
            image: employerImage,
            description: employer.address
          }]"/>
        </div>
      </div>
    </div>
  </section>
</template>

<script>
import Map from '@/components/Map';

export default {
  name: 'EmployerContacts',
  components: {Map},
  props: {
    employer: Object,
  },
  computed: {
    employerImage() {
      if (this.employer.images && this.employer.images.length > 0) {
        return this.employer.images[0].url;
      }
      if (this.employer.avatar) {
        return this.employer.avatar.url;
      }
      return null;
    },
  },
};
</script>

<style scoped>

</style>