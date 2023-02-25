<template>
  <v-select
    v-model="value_"
    @change="(v) => update(v)"
    :items="items"
    :loading="loading"
    label="Campo"
    outlined="outlined"
    dense="dense"
    hide-details="hide-details"
  ></v-select>
</template>

<script>
export default {
  props: {
    moduleId: {
      type: [String, Number],
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
    update(value) {
      this.loading = true;

      this.$rest("modules-fields")
        .put({
          id: this.fieldId,
          prop: "options",
          data: {
            value,
          },
          params: {
            moduleId: this.moduleId,
            option: "category",
          },
          save: false,
        })
        .then(() => {
          this.$emit("input", value);
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
