<template>
  <template-dialog-any
    @input="(data) => $emit('input', data)"
    :value="value"
    title="Opções"
    max-width="500px"
  >
    <template #actions></template>
    <div class="d-flex">
      <v-text-field
        class="mr-2"
        v-model="options.prefix"
        @keyup.enter="save('prefix')"
        :rules="[rules.url]"
        :disabled="loading.prefix"
        :loading="loading.prefix"
        ref="prefix"
        label="Prefixo"
        type="url"
        outlined="outlined"
        dense="dense"
        dark="dark"
      ></v-text-field>
      <div class="pt-1">
        <template-dialog-header-button
          @click="save('prefix')"
          icon="fas fa-save"
          text="Salvar"
        ></template-dialog-header-button>
      </div>
    </div>
  </template-dialog-any>
</template>

<script>
import { url } from "@/components/forms/rules";
import TemplateDialogAny from "../../templates/DialogAny";
import TemplateDialogHeaderButton from "../../templates/DialogHeaderButton";

export default {
  props: {
    value: Boolean,
    fieldId: {
      type: Number,
      required: true,
    },
    fieldOptions: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    rules: {
      url,
    },
    loading: {
      prefix: false,
    },
    options: {
      prefix: null,
    },
  }),
  computed: {
    module() {
      return this.$rest("modules").list.find(
        ({ key }) => key === this.moduleKey
      );
    },
    moduleId() {
      if (!this.module) {
        return null;
      }

      return this.module.id;
    },
    moduleKey() {
      return this.$route.params.module;
    },
  },
  methods: {
    save(option) {
      const el = this.$refs[option];
      if (!el.validate()) return;

      this.loading[option] = true;

      this.$rest("modulesFields")
        .put({
          id: this.fieldId,
          prop: "options",
          data: { option, value: this.options[option] },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() => {
          this.fieldOptions[option] = this.options[option];
          this.loading[option] = false;
        });
    },
  },
  watch: {
    fieldOptions(v) {
      this.options = { ...v };
    },
  },
  mounted() {
    this.options = { ...this.fieldOptions };
  },
  components: {
    TemplateDialogAny,
    TemplateDialogHeaderButton,
  },
};
</script>
