<template>
  <v-form ref="form">
    <v-text-field
      @keydown.enter="send"
      :rules="[rules.required, rules.email]"
      :loading="loading"
      prepend-inner-icon="fas fa-user"
      type="email"
      label="E-mail"
      name="email"
      autocomplete="email"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
    <v-text-field
      @keydown.enter="send"
      @click:append="show = !show"
      :rules="[rules.required]"
      :loading="loading"
      :append-icon="show ? 'fas fa-eye' : 'fas fa-eye-slash'"
      :type="show ? 'text' : 'password'"
      prepend-inner-icon="fas fa-lock"
      label="Senha"
      name="password"
      autocomplete="password"
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
      >Entrar</v-btn
    >
  </v-form>
</template>

<script>
import { required, email } from "./rules";

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
      email,
    },
    show: false,
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

<style>
.v-input__icon.v-input__icon--append button::before {
  font-size: 20px !important;
}
</style>
