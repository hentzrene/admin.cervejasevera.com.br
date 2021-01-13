<template lang="pug">
.grid-list(:style="style")
  slot
</template>

<script>
import { mixin, mountProps } from "../utils.js";

const propsCSS = {
  gap: "gap",
  colWidth: "--col-width",
};

export default {
  name: "grid-list",
  props: mountProps(Object.keys(propsCSS)),
  data: () => ({
    propsCSS,
  }),
  mixins: [mixin],
};
</script>

<style>
.grid-list {
  display: grid;
  grid-template-columns: repeat(auto-fit, var(--col-width));
  width: 100%;
  justify-content: center;
}
</style>
