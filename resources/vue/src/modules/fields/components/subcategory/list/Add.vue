<template>
  <template-dialog-any
    @input="(data) => $emit('input', data)"
    :value="value"
    title="Adicionar subcategoria"
    max-width="330px"
    simple="simple"
  >
    <v-select
      class="mb-3"
      v-model="category"
      :items="categories"
      :disabled="loading"
      label="Categoria"
      outlined="outlined"
      dense="dense"
      hide-details="hide-details"
    ></v-select>
    <v-text-field
      class="mb-3"
      v-model="title"
      :disabled="loading"
      label="Título"
      outlined="outlined"
      dense="dense"
      hide-details="hide-details"
    ></v-text-field>
    <v-btn
      class="text-none"
      @click="add"
      :disabled="!title || !category || loading"
      :loading="loading"
      color="secondary"
      block="block"
      depressed="depressed"
      >Adicionar</v-btn
    >
  </template-dialog-any>
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
