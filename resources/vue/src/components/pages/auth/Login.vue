<template>
  <v-sheet
    class="d-flex justify-center align-center pa-3"
    color="transparent"
    height="100%"
  >
    <v-card
      class="pa-4"
      height="max-content"
      width="320px"
      color="primary lighten-2"
      outlined="outlined"
    >
      <div class="d-flex justify-center">
        <img class="page-login-logo mb-3" :src="`${prefixPath}/img/logo.svg`" />
      </div>
      <div class="grey--text text--lighten-3 text-center mb-4">
        <div class="title">Painel de Administração</div>
        <div class="text-body-1">v{{ version }}</div>
      </div>
      <login-form @send="send" :loading="loading"></login-form>
    </v-card>
  </v-sheet>
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
