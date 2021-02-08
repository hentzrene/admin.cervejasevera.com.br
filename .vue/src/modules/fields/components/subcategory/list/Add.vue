<template lang="pug">
template-dialog-any(
  @input="(data) => $emit('input', data)",
  :value="value",
  title="Adicionar subcategoria",
  max-width="330px",
  simple
)
  v-select.mb-3(
    v-model="category",
    :items="categories",
    :disabled="loading",
    label="Categoria",
    outlined,
    dense,
    hide-details
  )
  v-text-field.mb-3(
    v-model="title",
    :disabled="loading",
    label="Título",
    outlined,
    dense,
    hide-details
  )
  v-btn.text-none(
    @click="add",
    :disabled="!title || !category || loading",
    :loading="loading",
    color="secondary",
    block,
    depressed
  ) Adicionar
</template>

<script>
import TemplateDialogAny from "../../../templates/DialogAny";

export default {
  props: {
    value: Boolean,
    moduleId: {
      type: Number,
      required: true,
    },
    fieldId: {
      type: Number,
      required: true,
    },
    categories: {
      type: Array,
      required: true,
    },
  },
  data: () => ({
    loading: false,
    category: null,
    title: null,
  }),
  methods: {
    add() {
      this.loading = true;
      this.$rest("modulesSubcategories")
        .post({
          data: {
            title: this.title,
          },
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
            categoryId: this.category,
          },
        })
        .finally(() => {
          this.title = null;
          this.loading = false;
        });
    },
  },
  components: {
    TemplateDialogAny,
  },
};
</script>
