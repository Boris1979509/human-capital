<template>
  <div class="portal-sections row">
    <div class="col-33">
      <router-link to="/kids" class="portal-sections__item portal-sections__item--kids">
        <div class="portal-sections__title">
          Детям
          <Icon xlink="link_arrow"
                viewport="0 0 9 16" />
        </div>

        <div class="portal-section__description">
          Школы, секции, кружки<br /> подготовительные курсы
        </div>
      </router-link>
    </div>

    <div class="col-33">
      <router-link to="/entrants" class="portal-sections__item portal-sections__item--entrants">
        <div class="portal-sections__title">
          Абитуриентам
          <Icon xlink="link_arrow"
                viewport="0 0 9 16" />
        </div>

        <div class="portal-section__description">
          ВУЗы, колледжи,<br /> подготовка к ЕГЭ
        </div>
      </router-link>
    </div>

    <div class="col-33">
      <router-link to="/adults" class="portal-sections__item portal-sections__item--adults">
        <div class="portal-sections__title">
          Взрослым
          <Icon xlink="link_arrow"
                viewport="0 0 9 16" />
        </div>

        <div class="portal-section__description">
          Второе высшее, MBA,<br /> магистратура, курсы
        </div>
      </router-link>
    </div>
  </div>
</template>

<script>
export default {
  name: "PortalSection",


}
</script>
