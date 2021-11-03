<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="330px"
)
  v-card.pa-4(dark)
    .title.mb-4.text-center Adicionar tag
    v-text-field.mb-3(
      v-model="addTagTitle",
      label="Título",
      outlined,
      dense,
      hide-details
    )
    v-btn.text-none(
      @click="add",
      :disabled="!addTagTitle",
      color="secondary",
      block,
      depressed
    ) Adicionar
  v-overlay(v-if="value", v-model="loading")
    loading
</template>

<script>
import Loading from "@/components/tools/Loading";

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
    addTagTitle: null,
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
  },
  methods: {
    add() {
      this.loading = true;
      this.$rest("modulesTags")
        .post({
          data: {
            title: this.addTagTitle,
          },
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
          },
        })
        .then((data) => this.$emit("add", data))
        .finally(() => {
          this.addTagTitle = null;
          this.addDialog = false;
          this.loading = false;
        });
    },
  },
  components: {
    Loading,
  },
};
</script>
