<template lang="pug">
v-form(ref="form")
  v-text-field(
    @keydown.enter="send",
    :rules="[rules.required, rules.email]",
    :loading="loading",
    type="email",
    label="E-mail",
    name="email",
    dense,
    outlined,
    dark
  )
  v-text-field(
    @keydown.enter="send",
    @click:append="show = !show"
    :rules="[rules.required]",
    :loading="loading",
    :append-icon="show ? 'fas fa-eye' : 'fas fa-eye-slash'"
    :type="show ? 'text' : 'password'"
    label="Senha",
    name="password",
    dense,
    outlined,
    dark
  )
  //- .d-flex.justify-space-between
    v-btn.text-none(to="/criar-conta", color="secondary", text, small) Criar conta
    v-btn.text-none(to="/esqueci-a-senha", color="secondary", text, small) Esqueci a senha
  v-btn.text-none.d-block.ml-auto(
    @click="send",
    :loading="loading",
    color="secondary",
    depressed,
    block
  ) Entrar
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
    show: false
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
