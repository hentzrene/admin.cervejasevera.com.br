<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="330px"
)
  v-card.pa-4(dark)
    .title.mb-4.text-center Adicionar menu
    v-text-field.mb-3(
      v-model="addMenuTitle",
      label="Título",
      outlined,
      dense,
      hide-details
    )
    v-btn.text-none(
      @click="add",
      :disabled="!addMenuTitle",
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
  },
  data: () => ({
    loading: false,
    addMenuTitle: null,
  }),
  methods: {
    add() {
      this.loading = true;
      this.$rest("modulesMenu")
        .post({
          data: {
            title: this.addMenuTitle,
          },
        })
        .then((data) => this.$emit("add", data))
        .finally(() => {
          this.addMenuTitle = null;
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
