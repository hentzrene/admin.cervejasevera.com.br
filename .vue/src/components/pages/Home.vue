<template lang="pug">
v-sheet.mx-auto.pa-4.d-flex.align-center(
  color="transparent",
  max-width="480px",
  height="100%"
)
  div(v-if="!modules.length").pa-4.white--text.font-weight-bold.primary.rounded-pill.text-body-1.mx-auto Nenhum módulo adicionado.
  grid-list(v-else col-width="100px", gap="16px")
    v-responsive(
      v-for="({ name, icon, key }, i) in modules",
      :aspect-ratio="1",
      :key="i",
      width="100px"
    )
      module-card(:to="'/' + key", :title="name", :icon="icon")
</template>

<script>
import ModuleCard from "@/components/cards/Module";

export default {
  computed: {
    modules() {
      return this.$rest("modules").list;
    },
  },
  beforeCreate() {
    this.$rest("modules").get();
  },
  components: {
    ModuleCard,
  },
};
</script>

<style>
</style>
