<template lang="pug">
v-form(ref="form")
  v-text-field(
    :rules="[rules.required]",
    :loading="loading",
    :disabled="loading",
    value="localhost",
    type="text",
    label="HOST",
    name="host",
    dense,
    outlined,
    dark
  )
  v-text-field(
    :rules="[rules.required]",
    :loading="loading",
    :disabled="loading",
    type="text",
    label="BANCO DE DADOS",
    name="database",
    dense,
    outlined,
    dark
  )
  v-text-field(
    :rules="[rules.required]",
    :loading="loading",
    :disabled="loading",
    type="text",
    label="USUÁRIO",
    name="user",
    dense,
    outlined,
    dark
  )
  v-text-field(
    :rules="[rules.required]",
    :loading="loading",
    :disabled="loading",
    type="password",
    label="SENHA",
    name="password",
    dense,
    outlined,
    dark
  )
  v-btn.text-none.d-block.ml-auto(
    @click="send",
    :loading="loading",
    color="secondary",
    depressed,
    block
  ) Confirmar
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
