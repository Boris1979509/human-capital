<template>
  <ProfileUserWrapper v-slot:default="props">
    <template v-if="props.favoritesCount>0">
      <UserFavoriteSelections class="section"/>

      <UserFavoriteContent :contentType="getNewsType()">
        <template slot="title">Новости</template>
      </UserFavoriteContent>

      <UserFavoriteContent :contentType="getArticleType()">
        <template slot="title">Статьи</template>
      </UserFavoriteContent>

      <UserFavoriteContent :contentType="getEventType()">
        <template slot="title">События</template>
      </UserFavoriteContent>

      <UserFavoriteInstitutions/>

      <UserFavoriteCurricula/>
    </template>
    <template v-else>
      <NothingFound text="Пока что вы ничего не добавили в избранное" />
    </template>
  </ProfileUserWrapper>
</template>

<script>
import UserFavoriteSelections from '../../../components/UserFavorites/UserFavoriteSelections';
import UserFavoriteContent from '../../../components/UserFavorites/UserFavoriteContent';
import UserFavoriteInstitutions from '../../../components/UserFavorites/UserFavoriteInstitutions';
import UserFavoriteCurricula from '../../../components/UserFavorites/UserFavoriteCurricula';

export default {
  name: 'ProfileUserFavoritesView',
  components: {UserFavoriteContent, UserFavoriteSelections, UserFavoriteInstitutions, UserFavoriteCurricula},
  methods: {
    getNewsType() {
      return process.env.VUE_APP_JOURNAL_NEWS_TYPE;
    },
    getArticleType() {
      return process.env.VUE_APP_JOURNAL_ARTICLE_TYPE;
    },
    getEventType() {
      return process.env.VUE_APP_JOURNAL_EVENT_TYPE;
    },
  },
};
</script>

<style scoped>
.section {
  margin-bottom: 24px;
}
</style>