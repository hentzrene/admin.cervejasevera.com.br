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
        v-model="options.regexp"
        @keyup.enter="save('regexp')"
        :rules="[rules.regexp]"
        :disabled="loading.regexp"
        :loading="loading.regexp"
        ref="regexp"
        label="RegExp"
        outlined="outlined"
        dense="dense"
        dark="dark"
      ></v-text-field>
      <div class="pt-1">
        <template-dialog-header-button
          @click="save('regexp')"
          icon="fas fa-save"
          text="Salvar"
        ></template-dialog-header-button>
      </div>
    </div>
  </template-dialog-any>
</template>

<script>
import { regexp } from "@/components/forms/rules";
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
      regexp,
    },
    loading: {
      regexp: false,
    },
    options: {
      regexp: null,
    },
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
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
