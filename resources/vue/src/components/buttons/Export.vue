<template lang="pug">
div
  toolbar-button(
    @click="dialog = true",
    tip="Exportar",
    icon="fas fa-file-export",
    dark
  )
  v-dialog(v-model="dialog", max-width="300px")
    v-card.pa-4(dark)
      .title.mb-4.d-flex.flex-column.flex-sm-row.justify-center Exportar Itens Ativos
      div
        v-select.mb-3(
          v-model="typeSelected",
          :items="types",
          :rules="[rules.required]",
          label="Tipo",
          name="type",
          outlined,
          dense,
          hide-details
        )
        v-select.mb-3(
          v-model="fieldsSelecteds",
          :items="parsedFields",
          :rules="[rules.required]",
          label="Campos",
          name="fields",
          multiple,
          outlined,
          dense,
          hide-details
        )
        v-btn.text-none(
          @click="exportation",
          :disabled="submitDisabled",
          :loading="loading",
          color="secondary",
          block,
          depressed
        ) Exportar
  a(ref="link", download="export")
</template>

<script>
import ToolbarButton from "./Toolbar";
import { required } from "../forms/rules";

export default {
  props: {
    moduleKey: {
      type: String,
      required: true,
    },
    fields: {
      type: Array,
      required: true,
    },
  },
  data: () => ({
    dialog: false,
    types: ["Excel"],
    typeSelected: "Excel",
    fieldsSelecteds: [],
    rules: {
      required,
    },
    loading: false,
  }),
  computed: {
    parsedFields() {
      const fields = this.fields.map(({ name, key }) => ({
        text: name,
        value: key,
      }));

      fields.unshift({ text: "Id", value: "id" });

      return fields;
    },
    submitDisabled() {
      return !this.typeSelected || !this.fieldsSelecteds.length;
    },
  },
  components: {
    ToolbarButton,
  },
  methods: {
    async exportation() {
      this.loading = true;

      const body = {
        type: this.typeSelected,
        fields: this.fieldsSelecteds,
      };

      const r = await fetch(`${this.server}/rest/${this.moduleKey}/export`, {
        method: "POST",
        body: JSON.stringify(body),
        headers: {
          Authorization: localStorage.getItem(`token`),
        },
      });
      this.loading = false;

      if (!r.ok) {
        const data = await r.json();
        this.$store.dispatch("endRequest", data);
      } else {
        const blob = await r.blob();

        const objectURL = URL.createObjectURL(blob);

        const a = this.$refs.link;
        a.href = objectURL;
        a.click();
        a.href = "";

        URL.revokeObjectURL(objectURL);
      }
    },
  },
};
</script>
