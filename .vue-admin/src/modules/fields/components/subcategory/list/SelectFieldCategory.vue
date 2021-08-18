<template lang="pug">
v-select(
  v-model="value_",
  @change="(v) => update(v)",
  :items="items",
  :loading="loading",
  label="Campo",
  outlined,
  dense,
  hide-details
)
</template>

<script>
export default {
  props: {
    moduleId: {
      type: Number,
      required: true,
    },
    fieldId: {
      type: Number,
      required: true,
    },
    value: Number,
    items: {
      type: Array,
      default: () => [],
    },
  },
  data: () => ({
    value_: null,
    loading: false,
  }),
  methods: {
    update(v) {
      this.loading = true;

      this.$rest("modules-fields")
        .put({
          id: this.fieldId,
          prop: "options",
          data: {
            value: v,
          },
          params: {
            moduleId: this.moduleId,
            option: "category",
          },
          save: false,
        })
        .finally(() => (this.loading = false));
    },
  },
  mounted() {
    this.value_ = this.value;
  },
};
</script>

<style></style>
