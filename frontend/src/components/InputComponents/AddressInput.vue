<template>
  <div>
    <TextInput class="invert"
               type="text"
               placeholder="Адрес"
               :value="value"
               :class="invalid ? 'error' : ''"
               @input="$emit('input', $event)"
               :isLabel="false"
               :required="true"
               @enter="geocode"
    />
    <yandex-map
        :class="invalid ? 'error' : ''"
        style="height: 460px; margin-bottom: 20px;"
        :controls="[
          'zoomControl',
        ]"
        :scroll-zoom="false"
        :settings="settings"
        @click="onClickMap"
        :coords="mapCenter"
        zoom="12"
    >
      <ymap-marker
          v-if="coords && coords.length"
          marker-id="Placemark"
          :coords="coords"
          :hint-content="value"
      />
    </yandex-map>
  </div>
</template>

<script>
import {yandexMap, ymapMarker, loadYmap} from 'vue-yandex-maps';
import settings from '../../yandex-map-settings';
export default {
  name: 'AddressInput',
  components: {yandexMap, ymapMarker},
  props: {
    value: String,
    coords: Array,
    invalid: Boolean,
  },
  data() {
    return {
      marker: null,
      settings: settings
    };
  },
  computed: {
    mapCenter() {
      return this.coords && this.coords.length ? this.coords : [37.626543, 55.753823];
    },
  },
  methods: {
    async onClickMap(e) {
      await loadYmap({...this.settings});
      let coords = e.get('coords');
      // eslint-disable-next-line no-undef
      ymaps.geocode(coords).then((res) => {
        let firstGeoObject = res.geoObjects.get(0);
        let address = firstGeoObject.getAddressLine();
        this.$emit('input', address);
        this.$emit('update:coords', coords);
      });
    },
    async geocode() {
      await loadYmap({...this.settings});
      // eslint-disable-next-line no-undef
      ymaps.geocode(this.value, {
        /**
         * Опции запроса
         * @see https://api.yandex.ru/maps/doc/jsapi/2.1/ref/reference/geocode.xml
         */
        results: 1,
      }).then((res) => {
        const firstGeoObject = res.geoObjects.get(0);
        this.$emit('input', this.value);
        this.$emit('update:coords', firstGeoObject.geometry.getCoordinates());
      });
    },
  },
};
</script>