<template lang="pug">
module-template(title="E-mail", max-width="400px")
  v-card.pa-4.pb-0.rounded-t-0(outlined, dark)
    v-form.d-flex.flex-column(ref="form")
    template(v-for="({ name, inputs }, i) in information")
      .white--text.font-weight-bold.text-body-1.mb-2(:key="i") {{ name }}
      template(v-for="({ value, rules, label, name, type }, i) in inputs")
        v-text-field(
          v-if="type === 'tinytext'",
          :key="i",
          :value="value",
          :rules="rules",
          :disabled="loading",
          :loading="loading",
          :label="label",
          :name="name",
          :type="type",
          outlined,
          dense
        )
        v-textarea(
          v-if="type === 'mediumtext'",
          :key="i",
          :value="value",
          :rules="rules",
          :disabled="loading",
          :loading="loading",
          :label="label",
          :name="name",
          :type="type",
          outlined,
          dense
        )
        v-file-input(
          v-else-if="type === 'img'",
          :key="i",
          :value="value",
          :rules="rules",
          :disabled="loading",
          :loading="loading",
          :label="label",
          :name="name",
          show-size,
          outlined,
          dense
        )
    v-card-actions
      v-btn.text-none.ml-auto(
        @click="submit",
        :disabled="loading",
        :loading="loading",
        color="secondary",
        depressed
      ) Salvar
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import { required, email } from "@/components/forms/rules";

export default {
  data: () => ({
    loading: true,
  }),
  computed: {
    information() {
      const information = this.$rest("information").item;
      console.log(information);
      return [
        {
          name: "Site",
          inputs: [
            {
              label: "Logo",
              name: "img",
              rules: [required, email],
              type: "img",
            },
            {
              label: "Compartilhamento",
              name: "share",
              rules: [required, email],
              type: "img",
            },
            { label: "Nome", name: "name", rules: [required, email] },
            {
              label: "Descrição",
              name: "description",
              rules: [required, email],
              type: "mediumtext",
            },
            {
              label: "Palavras Chave",
              name: "keywords",
              rules: [required, email],
              type: "mediumtext",
            },
          ],
        },
        {
          name: "Contato",
          inputs: [
            { label: "Telefone", name: "tel", type: "tinytext" },
            { label: "Whatsapp", name: "whatsapp", type: "tinytext" },
            { label: "E-mail", name: "email", type: "tinytext" },
            { label: "Facebook", name: "facebook", type: "tinytext" },
            { label: "Instagram", name: "instagram", type: "tinytext" },
            { label: "Youtube", name: "youtube", type: "tinytext" },
            { label: "Localização", name: "local", type: "tinytext" },
            {
              rules: [required, email],
            },
          ],
        },
      ];
    },
  },
  methods: {
    submit() {
      const form = this.$refs.form;
      if (form.validate()) {
        this.loading = true;

        this.$rest("information")
          .put({ id: "configuration", data: new FormData(form.$el) })
          .finally(() => (this.loading = false));
      }
    },
  },
  created() {
    this.loading = true;
    this.$rest("information")
      .get({ id: "configuration", save: (state, data) => (state.item = data) })
      .finally(() => (this.loading = false));
  },
  components: {
    ModuleTemplate,
  },
};
</script>

<style>
</style>
