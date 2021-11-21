<template>
  <tr @click="$router.push(`/profile/${type}/journal/${event.id}/registrations`)"
      class="resp-tab__row">
    <td class="title">{{ event.title }}</td>

    <td>
      {{ event.status }}
    </td>

    <td>
      {{ event.registration_available_till | dateTime }}
    </td>
    <td>
      {{ event.processed_registrations_count }}
    </td>
    <td>
      {{ event.registrations_count }}
    </td>

  </tr>
</template>
<script>
export default {
  name: 'EventRegistrationRow',
  props: {
    event: Object,
    type: String,
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
  padding: 16px;
}
.title{
  font-weight: bold !important;
}
</style>