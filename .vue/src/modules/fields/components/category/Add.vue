<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="330px"
)
  v-card.pa-4(dark)
    .title.mb-4.text-center Adicionar categoria
    v-text-field.mb-3(
      v-model="addCategoryTitle",
      label="Título",
      outlined,
      dense,
      hide-details
    )
    v-btn.text-none(
      @click="add",
      :disabled="!addCategoryTitle",
      color="secondary",
      block,
      depressed
    ) Adicionar
  v-overlay(v-if="value", v-model="loading")
    v-progress-circular(:size="50", color="secondary", indeterminate)
</template>

<script>
export default {
  props: {
    value: Boolean,
    fieldId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    loading: false,
    addCategoryTitle: null,
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
  },
  methods: {
    add() {
      this.loading = true;
      this.$rest("modulesCategories")
        .post({
          data: {
            title: this.addCategoryTitle,
          },
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
          },
        })
        .then((data) => this.$emit("add", data))
        .finally(() => {
          this.addCategoryTitle = null;
          this.addDialog = false;
          this.loading = false;
        });
    },
  },
};
</script>
