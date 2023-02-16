<template>
  <v-form ref="form">
    <v-text-field
      :rules="[rules.required]"
      :loading="loading"
      :disabled="loading"
      value="localhost"
      type="text"
      label="HOST"
      name="host"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
    <v-text-field
      :rules="[rules.required]"
      :loading="loading"
      :disabled="loading"
      type="text"
      label="BANCO DE DADOS"
      name="database"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
    <v-text-field
      :rules="[rules.required]"
      :loading="loading"
      :disabled="loading"
      type="text"
      label="USUÁRIO"
      name="user"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
    <v-text-field
      :loading="loading"
      :disabled="loading"
      type="password"
      label="SENHA"
      name="password"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
    <v-btn
      class="text-none d-block ml-auto"
      @click="send"
      :loading="loading"
      color="secondary"
      depressed="depressed"
      block="block"
      >Confirmar</v-btn
    >
  </v-form>
</template>

<script>
import { required } from "./rules";

export default {
  props: {
    loading: {
      type: Boolean,
      default: false,
    },
  },
  data: () => ({
    rules: {
      required,
    },
  }),
  methods: {
    send() {
      const form = this.$refs.form;
      if (form.validate()) {
        const data = Object.fromEntries(new FormData(form.$el).entries());
        this.$emit("send", data);
      }
    },
  },
};
</script>

<style></style>
