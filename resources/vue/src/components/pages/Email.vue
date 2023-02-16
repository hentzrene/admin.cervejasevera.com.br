<template>
  <module-template title="E-mail" max-width="400px">
    <v-card class="pa-4 pb-0 rounded-t-0" outlined="outlined" dark="dark">
      <v-form class="pt-4 d-flex flex-column" ref="form">
        <v-text-field
          :value="configuration.user"
          :rules="[rules.required, rules.email]"
          :disabled="loading"
          :loading="loading"
          label="Usuário"
          name="user"
          type="email"
          outlined="outlined"
          dense="dense"
        ></v-text-field>
        <v-text-field
          :value="configuration.password"
          :rules="[rules.required]"
          :disabled="loading"
          :loading="loading"
          label="Senha"
          name="password"
          type="password"
          outlined="outlined"
          dense="dense"
        ></v-text-field>
        <v-text-field
          :value="configuration.name"
          :rules="[rules.required]"
          :disabled="loading"
          :loading="loading"
          label="Nome"
          name="name"
          outlined="outlined"
          dense="dense"
        ></v-text-field>
        <v-text-field
          :value="configuration.host"
          :rules="[rules.required]"
          :disabled="loading"
          :loading="loading"
          label="Host"
          name="host"
          outlined="outlined"
          dense="dense"
        ></v-text-field>
        <v-text-field
          :value="configuration.smtpPort"
          :rules="[rules.required]"
          :disabled="loading"
          :loading="loading"
          label="Porta SMTP"
          name="smtpPort"
          type="number"
          outlined="outlined"
          dense="dense"
        ></v-text-field>
        <v-text-field
          :value="configuration.recipient"
          :rules="[rules.required, rules.email]"
          :disabled="loading"
          :loading="loading"
          label="Destinatário"
          name="recipient"
          outlined="outlined"
          dense="dense"
        ></v-text-field>
      </v-form>
      <v-card-actions>
        <v-btn
          class="text-none ml-auto"
          @click="submit"
          :disabled="loading"
          :loading="loading"
          color="secondary"
          depressed="depressed"
          >Salvar</v-btn
        >
      </v-card-actions>
    </v-card>
  </module-template>
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import { required, email } from "@/components/forms/rules";

export default {
  data: () => ({
    rules: {
      required,
      email,
    },
    loading: true,
  }),
  computed: {
    configuration() {
      return this.$rest("configuration").item;
    },
  },
  methods: {
    submit() {
      const form = this.$refs.form;
      if (form.validate()) {
        this.loading = true;

        this.$rest("configuration")
          .put({ id: "email", data: new FormData(form.$el) })
          .finally(() => (this.loading = false));
      }
    },
  },
  created() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }

    this.loading = true;
    this.$rest("configuration")
      .get({ id: "email", save: (state, data) => (state.item = data) })
      .finally(() => (this.loading = false));
  },
  components: {
    ModuleTemplate,
  },
};
</script>

<style></style>
