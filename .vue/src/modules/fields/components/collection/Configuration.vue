<template lang="pug">
template-dialog-any(
  @input="(data) => $emit('input', data)",
  :value="value",
  title="Configurações",
  max-width="330px",
  simple
)
  v-select.mb-3(
    @change="(v) => setOption('itemsType', v)",
    :items="types",
    :value="fieldOptions.itemsType",
    :disabled="loading",
    label="Tipo dos itens",
    outlined,
    dense,
    hide-details
  )
</template>

<script>
import TemplateDialogAny from "../../templates/DialogAny";

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
    moduleId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    loading: false,
  }),
  computed: {
    types() {
      return this.$rest("modulesFieldsTypes")
        .list.filter(({ foreign }) => foreign == 0)
        .map(({ id, name }) => ({
          text: name,
          value: id,
        }));
    },
  },
  methods: {
    setOption(key, value) {
      this.loading = true;
      this.$rest("modulesFields")
        .put({
          id: this.fieldId,
          prop: "options",
          data: {
            option: key,
            value,
          },
          params: {
            moduleId: this.moduleId,
          },
        })
        .finally(() => (this.loading = false));
    },
  },
  components: {
    TemplateDialogAny,
  },
};
</script>
