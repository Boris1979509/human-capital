<template>
  <div class="cv" @hover="$emit('seen')">
    <div class="work-experience">
      <div class="row-cv">
        <div class="left-column">
          <div class="experience-title">
            Опыт работы
          </div>
          <div class="experience-value">
            {{ cv.total_experience }}
          </div>
        </div>
      </div>

      <div class="row-cv" v-for="(job, i) in cv.job" :key="i">
        <div class="left-column">
          <div class="work-item-dates">{{ job.period }}</div>
          <div class="work-item-duration">{{ job.period_duration }}</div>
        </div>
        <div class="right-column">
          <div class="work-item-company">{{ job.company }}</div>
          <div class="work-item-site">{{ job.company_website }}</div>
          <div class="work-item-position">{{ job.position }}</div>
          <div class="work-item-description">{{ job.description }}</div>
        </div>
      </div>
    </div>

    <div class="education">
      <div class="education-title">Образование</div>
      <div v-for="(education, i) in cv.education" :key="i" class="row-cv">
        <div class="left-column">
          <div class="work-item-dates">{{ education.quality }}</div>
          <div class="work-item-duration">{{ education.year }}</div>
        </div>
        <div class="right-column">
          <div class="work-item-company">{{ education.university }}</div>
          <div class="work-item-position">{{ education.specialty }}</div>
        </div>
      </div>
    </div>
    <div class="title">
      Ключевые навыки
    </div>
    <div class="text">{{ cv.skills }}</div>
    <div class="title">
      Дополнительная информация
    </div>
    <div class="text">{{ cv.description }}</div>
  </div>
</template>

<script>
export default {
  name: 'GeneratedCV',
  props: {
    cv: Object,
  },
};
</script>

<style scoped>

#footer {
  position: fixed;
  bottom: 0;
  left: 0;
  right: 0;
  height: 48px;
}

.container {
  font-family: Golos;
}

.row-cv {
  margin-bottom: 16px;
  /*display: flex;*/
  /*flex-direction: row;*/
}

.row-cv:after {
  content: "";
  display: table;
  clear: both;
}

.left-column {
  width: 200px;
  float: left;
}

.right-column {
  width: 345px;
  float: left;
}

.avatar img {
  border-radius: 87px;

  width: 174px;
  height: 174px;
  object-fit: contain;
}

.experience-title {
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  margin-bottom: 4px;
}

.experience-value {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.48;
}

.work-item-dates {
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  margin-bottom: 4px;
}

.work-item-duration {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.72;
}

.work-item-company {
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  margin-bottom: 4px;
}

.work-item-site {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.48;
  margin-bottom: 4px;
}

.work-item-position {
  margin-bottom: 12px;
}

.work-item-position, .work-item-description {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.72;
}

.work-experience {
  margin-bottom: 20px;
}

.education-title {
  margin-bottom: 12px;
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
}

.title {
  font-style: normal;
  font-weight: bold;
  font-size: 16px;
  line-height: 28px;
  color: #04153E;
  margin-bottom: 16px;
}

.text {
  font-style: normal;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: #04153E;
  opacity: 0.72;
  margin-bottom: 20px;
}
</style>