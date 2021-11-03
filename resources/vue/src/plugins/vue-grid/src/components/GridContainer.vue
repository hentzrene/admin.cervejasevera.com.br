<template lang="pug">
.grid-container(:style="style", :class="classes")
  div(
    v-for="area in gridAreas",
    :key="area",
    :style="{ gridArea: area }",
    :class="'grid-area-' + area"
  )
    slot(:name="area")
  slot(name="default")
</template>

<script>
import mixin from "./mixin.js";
import mountProps from "../utils/mountProps.js";

const propsCSS = {
  cols: {
    type: String,
  },
  rows: {
    type: String,
  },
  autoRows: {
    type: String,
  },
  autoCols: {
    type: String,
  },
  gap: {
    type: String,
  },
  autoFlow: {
    type: String,
  },
  justify: {
    type: String,
  },
  alignItems: {
    type: String,
  },
  alignContent: {
    type: String,
  },
  layout: {
    type: Array,
    default: () => [],
    parse: (v) =>
      v
        .map((row) => row.join(" "))
        .map((r) => `"${r}"`)
        .join(" "),
  },
};

export default {
  name: "grid-container",
  props: mountProps(propsCSS),
  data: () => ({
    propsCSS,
  }),
  computed: {
    gridAreas() {
      return [...new Set(this.layout.flat())];
    },
  },
  mixins: [mixin],
};
</script>

<style>
@import url(../style/grid-container.css);
</style>
