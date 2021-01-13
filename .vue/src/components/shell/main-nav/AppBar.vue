<template lang="pug">
v-app-bar(color="primary lighten-2", fixed, app, dense)
  router-link.h-100.white--text(v-if="sm", to="/")
    img.py-1(:src="`/admin/img/logo.svg`", height="100%", contain)
  v-spacer
  v-menu(
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
  },
  methods: {
    logout() {
      this.loading = true;
      this.$auth
        .logout()
        .then(() => this.$router.push("/admin/entrar"))
        .finally(() => (this.loading = false));
    },
  },
};
</script>
