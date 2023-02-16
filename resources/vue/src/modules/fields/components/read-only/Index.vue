<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <v-text-field
      :label="label"
      :value="value"
      readonly="readonly"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
  </grid-item>
</template>

<script>
import mixin from "../../mixin";

export default {
  mixins: [mixin],
};
</script>
