<template lang="pug">
.grid-item(:style="style")
  slot
</template>

<script>
import { mixin, mountProps } from "../utils.js";

const propsCSS = {
  colStart: "--col-start",
  colEnd: "--col-end",
  rowStart: "--row-start",
  rowEnd: "--row-end",
};

export default {
  name: "grid-item",
  props: mountProps(Object.keys(propsCSS)),
  data: () => ({
    propsCSS,
  }),
  mixins: [mixin],
};
</script>

<style>
.grid-item {
  grid-column-start: span var(--col-start);
  grid-column-end: span var(--col-end);
  grid-row-start: span var(--row-start);
  grid-row-end: span var(--row-end);
}
</style>
