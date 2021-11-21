<template>
  <div class="main-info row">
    <div class="col-58">
      <h1 class="name">{{ curriculum.name }}</h1>
      <div class="type">{{ curriculum.type.name }}</div>
      <div class="description" v-html="curriculum.description"></div>
      <CurriculumMainInfoCompetitions :competitions="curriculum.competitions"/>
    </div>
    <div class="right">
      <Button class="btn--light">Задать вопрос</Button>
      <Favorite
          v-if="curriculum.id"
          :initialFavorited="curriculum.is_favorited"
          type="curriculum"
          :itemId="curriculum.id"
          style="margin-top: 26px;"
      />

      <AddToCalendar
          v-if="curriculum.id"
          :initial-state="curriculum.is_calendar_entry"
          type="curriculum"
          :itemId="curriculum.id"
      />

      <SocialSharing
          :link="`https://hcap.d.rusatom.dev/programs/${$route.params.id}`"
          :title="curriculum.name"
          :image="this.curriculum.institution.avatar"
          description="Откройте для себя образовательные возможности региона"
          :label="true"
      />
      <CurriculumMainInfoCounters :curriculum="curriculum"/>
    </div>
  </div>
</template>

<script>
import CurriculumMainInfoCompetitions from './CurriculumMainInfoCompetitions';
import CurriculumMainInfoCounters from './CurriculumMainInfoCounters';
import Favorite from '../Favorite';
import AddToCalendar from '@/components/AddToCalendar';

export default {
  name: 'CurriculumMainInfo',
  components: {CurriculumMainInfoCompetitions, CurriculumMainInfoCounters, Favorite, AddToCalendar},
  props: {
    curriculum: Object,
  },
};
</script>

<style scoped lang="scss">
.main-info {
  margin-top: 32px;
  justify-content: space-between;
}

.right {
  float: right;
  min-height: 1px;
  max-width: 361px;
  padding: 0 34px;
}

.name {
  font-style: normal;
  font-weight: 800;
  font-size: 40px;
  line-height: 44px;
}

.type {
  margin-top: 16px;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  opacity: 0.48;
}

.description {
  margin-top: 32px;
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 24px;
  white-space: pre-line;
}
</style>
