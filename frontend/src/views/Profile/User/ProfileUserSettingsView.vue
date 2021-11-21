<template>
  <ProfileUserWrapper>
    <h4 class="title">
      Настройка рассылок
    </h4>

    <section class="profile__section">
      <div class="row">
        <div class="col-100">
          <Checkbox id="subscription"
                    label="Актуальные новости и нововведения портала"
                    description="Рассылка не будет приходить чаще раза в неделю" />
        </div>
      </div>
    </section>

    <section class="profile__section profile__section--logout">
      <div class="row">
        <div class="col-100">
          <Button @click.native="logOut" class="link-svg">
            <Icon xlink="logout"
                  viewport="0 0 16 16" />
            Выйти из профиля
          </Button>
        </div>
      </div>
    </section>
  </ProfileUserWrapper>
</template>

<script>
export default {
  name: "ProfileUserSettingsView",

  methods: {
    logOut: function() {
      this.$store.dispatch('LOG_ME_OUT')
          .then(() => {
            this.$router.push('/login');
          })
          .catch(err => console.log(err));
    }
  },
}
</script>
