<template lang="pug">
.grid-list(:style="style", :class="classes")
  slot
</template>

<script>
import mixin from "./mixin.js";
import mountProps from "../utils/mountProps.js";

const propsCSS = {
  colWidth: {
    type: String,
  },
  rowHeight: {
    type: String,
  },
  gap: {
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
};

export default {
  name: "grid-list",
  props: mountProps(propsCSS),
  data: () => ({
    propsCSS,
  }),
  mixins: [mixin],
};
</script>

<style>
@import url(../style/grid-list.css);
</style>
