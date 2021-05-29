<template lang="pug">
v-sheet.mx-auto.pa-4.d-flex.align-center(
  color="transparent",
  max-width="960px",
  height="100%"
)
  loading.mx-auto(v-if="loading")
  .pa-4.white--text.font-weight-bold.primary.rounded-pill.text-body-1.mx-auto(
    v-else-if="!modules.length"
  ) Nenhum módulo adicionado.
  grid-list.py-4(v-else, col-width="150px", gap="16px")
    v-responsive(
      v-for="({ name, icon, key }, i) in modules",
      :aspect-ratio="1",
      :key="i",
      width="150px"
    )
      module-card(:to="'/' + key", :title="name", :icon="icon")
</template>

<script>
import Loading from "@/components/tools/Loading";
import ModuleCard from "@/components/cards/Module";

export default {
  data: () => ({ loading: true }),
  computed: {
    modules() {
      return this.$rest("modules").list;
    },
  },
  beforeCreate() {
    this.$rest("modules")
      .get()
      .finally(() => (this.loading = false));
  },
  components: {
    Loading,
    ModuleCard,
  },
};
</script>

<style>
</style>
