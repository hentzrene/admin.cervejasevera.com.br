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
      <div class="title grey--text text--lighten-3 text-center mb-4">
        Configurar Banco de Dados
      </div>
      <setup-form @send="send" :loading="loading"></setup-form>
    </v-card>
  </v-sheet>
</template>

<script>
import SetupForm from "@/components/forms/Setup";

export default {
  data: () => ({
    loading: false,
  }),
  created() {
    if (this.$auth.on || this.installed) this.$router.replace("/");
  },
  methods: {
    send(data) {
      this.loading = true;
      this.$rest("setup")
        .post({ data })
        .then(() => {
          this.installed = true;
          this.$router.push("/entrar");
        })
        .finally(() => (this.loading = false));
    },
  },
  components: {
    SetupForm,
  },
};
</script>

<style>
.page-login-logo {
  max-height: 50px;
  object-fit: contain;
}
</style>
