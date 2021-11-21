<template>
  <tr @click="$router.push(`/profile/employer/vacancies/${vacancy.id}/responses`)"
      class="resp-tab__row">
    <td>
      <div class="resp-tab__main">
        <div class="resp-tab__main--type">
          {{ vacancy.name }}
        </div>

        <div class="resp-tab__main--info">
          <span v-if="vacancy.salary_min">{{ vacancy.salary_min }} ₽</span> <span>• {{
            vacancy.employer.name
          }}</span>
        </div>
      </div>
    </td>

    <td>
      {{ vacancy.city.name }}
    </td>

    <td>
      -
    </td>

    <td>
      {{ vacancy.views }}
    </td>

    <td>
      {{ vacancy.responses_count }}
    </td>

    <td>

    </td>
  </tr>
</template>
<script>
export default {
  name: 'VacancyRowForEmployer',
  props: {
    vacancy: Object,
  },
};
</script>
<style lang="scss">

.resp-tab th, .resp-tab td {
  padding: 4px 10px;
  border: none;
}

.resp-tab th {
  font-weight: 500;
  font-size: 13px;
  line-height: 16px;
  color: var(--main-color-dark-trans-light);
  text-align: left;
  vertical-align: top;
}

.resp-tab td {
  vertical-align: middle;
  font-weight: normal;
  font-size: 16px;
  line-height: 20px;
  color: var(--main-color-dark);
}
</style>