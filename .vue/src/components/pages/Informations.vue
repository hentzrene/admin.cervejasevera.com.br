<template lang="pug">
module-template(title="E-mail", width="800px", max-width="100%")
  v-card.pa-4.pb-0.rounded-t-0(outlined, dark)
    v-form.page-information.d-flex.flex-column(ref="form")
      .page-informations-section(
        v-for="{ name, inputs } in information",
        :key="name"
      )
        grid-item(col-end=2)
          .white--text.font-weight-bold.text-body-1.mb-2 {{ name }}
        template(v-for="({ value, rules, label, name, type }, i) in inputs")
          grid-item(v-if="type === 'tinytext'", col-end=2, col-end-sm=1)
            v-text-field(
              :key="i",
              :value="value",
              :rules="rules",
              :disabled="loading",
              :loading="loading",
              :label="label",
              :name="name",
              outlined,
              dense
            )
          grid-item(v-else-if="type === 'mediumtext'", col-end=2)
            v-textarea(
              :key="i",
              :value="value",
              :rules="rules",
              :disabled="loading",
              :loading="loading",
              :label="label",
              :name="name",
              outlined,
              dense
            )
          grid-item(
            v-else-if="type === 'img'",
            row-end=4,
            col-end=2,
            col-end-sm=1
          )
            v-file-input(
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
import { email, url, phone } from "@/components/forms/rules";

export default {
  data: () => ({
    loading: true,
  }),
  computed: {
    information() {
      const information = this.$rest("configuration").item;

      return [
        {
          name: "Site",
          inputs: [
            {
              label: "Nome",
              name: "name",
              type: "tinytext",
              value: information["name"],
            },
            // {
            //   label: "Logo",
            //   name: "img",
            //   type: "img",
            // },
            // {
            //   label: "Compartilhamento",
            //   name: "share",
            //   type: "img",
            // },
            {
              label: "Descrição",
              name: "description",
              type: "mediumtext",
              value: information["description"],
            },
            {
              label: "Palavras Chave",
              name: "keywords",
              type: "mediumtext",
              value: information["keywords"],
            },
          ],
        },
        {
          name: "Contato",
          inputs: [
            {
              label: "Telefone",
              name: "tel",
              type: "tinytext",
              rules: [phone],
              value: information["tel"],
            },
            {
              label: "Whatsapp",
              name: "whatsapp",
              type: "tinytext",
              rules: [phone],
              value: information["whatsapp"],
            },
            {
              label: "E-mail",
              name: "email",
              type: "tinytext",
              rules: [email],
              value: information["email"],
            },
            {
              label: "Facebook",
              name: "facebook",
              type: "tinytext",
              rules: [url],
              value: information["namfacebooke"],
            },
            {
              label: "Instagram",
              name: "instagram",
              type: "tinytext",
              rules: [url],
              value: information["instagram"],
            },
            {
              label: "Youtube",
              name: "youtube",
              type: "tinytext",
              rules: [url],
              value: information["youtube"],
            },
            {
              label: "Localização",
              name: "local",
              type: "tinytext",
              value: information["local"],
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

        this.$rest("configuration")
          .put({ id: "informations", data: new FormData(form.$el) })
          .finally(() => (this.loading = false));
      }
    },
  },
  created() {
    this.loading = true;
    this.$rest("configuration")
      .get({ id: "informations", save: (state, data) => (state.item = data) })
      .finally(() => (this.loading = false));
  },
  components: {
    ModuleTemplate,
  },
};
</script>

<style>
.page-informations-section {
  display: grid;
  grid-template-columns: repeat(2, calc(50% - 16px / 2));
  column-gap: 16px;
  grid-auto-flow: dense;
}
</style>
