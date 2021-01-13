<template lang="pug">
v-sheet.mx-auto.pa-4.d-flex.align-center(
  color="transparent",
  max-width="480px",
  height="100%"
)
  grid-list(col-width="100px", gap="16px")
    v-responsive(
      v-for="({ name, icon, key }, i) in modules",
      :aspect-ratio="1",
      :key="i",
      width="100px"
    )
      module-card(:to="'/admin/' + key", :title="name", :icon="icon")
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
