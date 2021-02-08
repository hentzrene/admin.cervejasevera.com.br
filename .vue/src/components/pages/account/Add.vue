<template lang="pug">
module-template(title="Contas / Adicionar", width="800px", max-width="100%")
  v-card.pa-4.rounded-t-0(outlined, dark)
    v-form.pt-4(ref="form")
      v-row
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            label="Nome",
            name="name",
            :rules="[rules.required]",
            outlined,
            dense
          )
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            label="E-mail",
            name="email",
            :rules="[rules.required, rules.email]",
            outlined,
            dense
          )
      v-text-field(
        label="Senha",
        name="password",
        :rules="[rules.required]",
        outlined,
        dense
      )
      .d-flex.flex-wrap
        v-checkbox.mx-2(
          v-for="({ id, name }, i) in modules",
          v-model="permissions",
          :key="i",
          :label="name",
          :value="id"
        )
      .d-flex.justify-end
        v-btn.text-none(@click="send", color="secondary", depressed) Adicionar
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import { required, email } from "@/components/forms/rules.js";

export default {
  name: "AccountAdd",
  data: () => ({
    rules: {
      required,
      email,
    },
    permissions: [],
  }),
  computed: {
    modules() {
      return this.$rest("modulesBasic").list;
    },
  },
  methods: {
    send() {
      const form = this.$refs.form;
      if (form.validate()) {
        const data = Object.fromEntries(new FormData(form.$el).entries());
        data.permissions = this.permissions;
        this.$rest("accounts")
          .post({ data })
          .then(() => this.$router.push("/accounts"));
      }
    },
  },
  beforeCreate() {
    this.$rest("modulesBasic").get();
  },
  components: {
    ModuleTemplate,
  },
};
</script>

<style>
</style>
