<template>
  <yandex-map
      v-if="mapCenter"
      style="height: 460px; margin-bottom: 20px;"
      :controls="[
          'zoomControl',
        ]"
      :scroll-zoom="false"
      :settings="settings"
      :coords="mapCenter"
      :zoom="18"
  >
    <ymap-marker
        v-for="(marker, i) in markers"
        :marker-id="i"
        :key="i"
        :coords="marker.coords"
        cluster-name="balloon"
    >
      <MapBalloon
          slot="balloon"
          :image="marker.image"
          :title="marker.title"
          :subtitle="marker.subtitle"
          :description="marker.description"
      />
    </ymap-marker>
  </yandex-map>
</template>

<script>
import {yandexMap, ymapMarker} from 'vue-yandex-maps';
import settings from '../yandex-map-settings';
import MapBalloon from '@/components/MapBalloon';

export default {
  name: 'Map',
  components: {MapBalloon, yandexMap, ymapMarker},
  props: {
    markers: Array,
  },
  data() {
    return {
      settings: settings,
      mapCenter: null,
    };
  },
  mounted() {
    this.mapCenter = this.getMapCenter();
  },
  methods: {
    getMapCenter() {
      if (this.markers.length === 1) {
        return this.markers[0].coords;
      }
      return [37.626543, 55.753823];
    },
  },
};
</script>

<style scoped>

</style>