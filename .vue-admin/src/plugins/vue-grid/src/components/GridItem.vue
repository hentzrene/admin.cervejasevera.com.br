<template lang="pug">
.grid-item(:style="style", :class="classes")
  slot
</template>

<script>
import mixin from "./mixin.js";
import mountProps from "../utils/mountProps.js";

const propsCSS = {
  colStart: {
    type: String,
  },
  colEnd: {
    type: String,
  },
  rowStart: {
    type: String,
  },
  rowEnd: {
    type: String,
  },
};

export default {
  name: "grid-item",
  props: mountProps(propsCSS),
  data: () => ({
    propsCSS,
  }),
  mixins: [mixin],
};
</script>

<style>
@import url(../style/grid-item.css);
</style>
