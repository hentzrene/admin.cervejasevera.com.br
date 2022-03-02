<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="330px"
)
  v-card.pa-4(dark)
    .title.mb-4.text-center Alterar Título
    v-text-field.mb-3(
      :value="img.title",
      @input="(v) => (title = v)",
      label="Título",
      outlined,
      dense,
      hide-details
    )
    v-btn.text-none(
      @click="submit",
      :disabled="!title",
      color="secondary",
      block,
      depressed
    ) Alterar
  v-overlay(v-if="value", v-model="loading")
    loading
</template>

<script>
import Loading from "@/components/tools/Loading";

export default {
  props: {
    value: Boolean,
    img: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    loading: false,
    title: null,
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
  },
  methods: {
    submit() {
      this.loading = true;
      this.$rest("modulesImages")
        .put({
          id: this.img.id,
          prop: "title",
          data: {
            value: this.title,
          },
        })
        .then(() => {
          this.img.title = this.title;
          this.$emit("input", false);
        })
        .finally(() => (this.loading = false));
    },
  },
  components: {
    Loading,
  },
};
</script>
