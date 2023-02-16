<template>
  <grid-item col-end="span 2">
    <v-textarea
      :label="label"
      :value="value"
      :name="name"
      counter="300"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-textarea>
  </grid-item>
</template>

<script>
import mixin from "../../mixin";

export default {
  mixins: [mixin],
};
</script>
