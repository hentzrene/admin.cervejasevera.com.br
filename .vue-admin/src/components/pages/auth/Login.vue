<template lang="pug">
v-sheet.d-flex.justify-center.align-center.pa-3(
  color="transparent",
  height="100%"
)
  v-card.pa-4(
    height="max-content",
    width="320px",
    color="primary lighten-2",
    outlined
  )
    .d-flex.justify-center
      img.page-login-logo.mb-3(src="/admin/img/logo.svg")
    .grey--text.text--lighten-3.text-center.mb-4
      .title Painel de Administração
      .text-body-1 v{{ version }}
    login-form(@send="send", :loading="loading")
</template>

<script>
import LoginForm from "@/components/forms/Login";

export default {
  data: () => ({
    loading: false,
  }),
  methods: {
    send(data) {
      this.loading = true;
      this.$auth
        .login(data)
        .then((data) => {
          this.$store.dispatch("setInfoUser", data);
          this.$router.push("/");
        })
        .finally(() => (this.loading = false));
    },
  },
  created() {
    if (this.$auth.on) this.$router.replace("/");
  },
  components: {
    LoginForm,
  },
};
</script>

<style>
.page-login-logo {
  max-height: 50px;
  object-fit: contain;
}
</style>
