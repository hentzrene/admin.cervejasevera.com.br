<template lang="pug">
v-app-bar(color="primary lighten-2", fixed, app, dense)
  router-link.h-100.white--text(v-if="sm", to="/")
    img.py-2(:src="`/admin/img/logo.svg`", height="100%", contain)
  .grey--text.text--lighten-3.text-center.text-caption.ml-3 MRX CMS Headless {{ version }}
  v-spacer
  v-menu(
    v-model="menu",
    :close-on-content-click="false",
    min-width="200px",
    rounded,
    offset-y,
    bottom
  )
    template(v-slot:activator="{ on }")
      v-btn(icon, x-large, v-on="on")
        v-avatar(color="accent", size="42")
          span.white--text {{ userInitial }}
    v-card
      v-list-item-content.justify-center.pb-0.primary.lighten-2.white--text
        .mx-auto.text-center
          v-avatar.mb-2(color="accent")
            span.white--text {{ userInitial }}
          h4 {{ user.name }}
          p.caption.mt-1 {{ user.email }}
          v-divider
          template(v-if="user.type == 1")
            v-btn.text-none(
              @click="$router.push('/accounts') && (menu = false)",
              color="white",
              depressed,
              text,
              block,
              tile
            )
              v-icon(left) fas fa-users
              span Contas
            v-divider
            v-btn.text-none(
              @click="$router.push('/modules') && (menu = false)",
              color="white",
              depressed,
              text,
              block,
              tile
            )
              v-icon(left) fas fa-cogs
              span Módulos
            v-divider
            v-btn.text-none(
              @click="$router.push('/email') && (menu = false)",
              color="white",
              depressed,
              text,
              block,
              tile
            )
              v-icon(left) fas fa-envelope
              span E-mail
            v-divider
            v-btn.text-none(
              @click="$router.push('/tags') && (menu = false)",
              color="white",
              depressed,
              text,
              block,
              tile
            )
              v-icon(left) fas fa-code
              span Tags
            v-divider
          v-btn.text-none(
            @click="logout",
            :loading="loading",
            color="white",
            depressed,
            text,
            block,
            tile
          )
            v-icon(left) fas fa-sign-out-alt
            span Sair
  v-app-bar-nav-icon(
    v-if="sm",
    @click="() => $emit('toggledrawer')",
    color="grey lighten-2"
  )
</template>

<script>
export default {
  data: () => ({
    loading: false,
    menu: false,
  }),
  computed: {
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
    user() {
      return this.$store.state.user;
    },
    userInitial() {
      return this.user.name.slice(0, 1);
    },
    version() {
      return process.env.VUE_APP_PROJECT_VERSION;
    },
  },
  methods: {
    logout() {
      this.loading = true;
      this.$auth
        .logout()
        .then(() => this.$router.push("/entrar"))
        .finally(() => (this.loading = false));
    },
  },
};
</script>
