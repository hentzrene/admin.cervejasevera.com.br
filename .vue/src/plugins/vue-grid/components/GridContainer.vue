<template lang="pug">
.grid-container(:style="style")
  slot
</template>

<script>
import { mixin, mountProps } from "../utils.js";

const propsCSS = {
  cols: "grid-template-cols",
  rows: "grid-template-rows",
  autoRows: "auto-rows",
  autoColumns: "auto-columns",
  gap: "gap",
  autoFlow: "auto-flow",
  justify: "justify-content",
  alignItems: "align-items",
  alignContent: "align-content",
};

export default {
  name: "grid-container",
  props: mountProps(Object.keys(propsCSS)),
  data: () => ({
    propsCSS,
  }),
  mixins: [mixin],
};
</script>

<style>
.grid-container {
  display: grid !important;
}
</style>
