<template>
  <div class="row">
    <div class="col-58">
      <PersonCard :image="opinionMain.image"
                  :name="opinionMain.name"
                  :position="opinionMain.position"
                  :title="opinionMain.title"
                  :description="opinionMain.description" />
    </div>

    <div class="col-41">
      <PersonCardSmall v-for="(card, key) in opinions" :key="key"
                       :image="card.image"
                       :name="card.name"
                       :position="card.position"
                       :title="card.title" />
    </div>
  </div>
</template>

<script>
export default {
  name: 'Opinions',

  data: function() {
    return {
      opinionMain: {
        id: 1,
        image: require('@/assets/img/avatar.png'),
        name: 'Александр Петренко',
        position: 'Преподаватель МГУ, Профессор',
        title: 'Директор по маркетингу — профессия или должность? Ей надо учиться или на неё назначают?',
        description: 'Разбираемся в двойственности позиции «директор по маркетингу» и её особенностях',
      },

      opinions: [
        {
          id: 2,
          image: require('@/assets/img/avatar.png'),
          name: 'Ольга Смирнова',
          position: 'Декан факультета информационных технологий',
          title: 'IT-проекты в госсекторе: как победить бюрократию и не уйти в минус',
        },
        {
          id: 3,
          image: require('@/assets/img/avatar.png'),
          name: 'Константин Перов',
          position: 'Ректор МГИУ',
          title: 'Почём рыбка? Разбираемся, что заложить в стоимость продукта в IT',
        },
        {
          id: 4,
          image: require('@/assets/img/avatar.png'),
          name: 'Александр Константинопольский',
          position: 'Преподаватель МГУ, Профессор кафедры информатики',
          title: 'IT-проекты в госсекторе: как победить бюрократию и не уйти в минус',
        },
      ],
    }
  },
}
</script>
