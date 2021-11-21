<template>
    <div class="row">
      <div class="col-100">
        <EmployersCatalog />
      </div>
      <div class="col-100">
        <EmployersEvents />
      </div>
    </div>
</template>

<script>
import EmployersCatalog from '@/components/EmployerParts/EmployersCatalog';
import EmployersEvents from '@/components/EmployerParts/EmployersEvents';

export default {
  name: 'EmployersCatalogView',

  components: {
    EmployersEvents,
    EmployersCatalog,
  },

  data() {
    return {
      tabs: [
        {
          id: 1,
          name: 'Работодатели',
          route: '/job/employers/',
        },
        {
          id: 2,
          name: 'Вакансии',
          route: '/job/vacancies/',
        },
      ]

    }
  },

  methods: {

  }
}
</script>
