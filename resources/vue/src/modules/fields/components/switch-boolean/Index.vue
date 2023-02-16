<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <v-switch
      v-model="value_"
      :label="label"
      color="accent"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-switch>
    <input type="hidden" :name="name" :value="parseInt(value_ | 0)" />
  </grid-item>
</template>

<script>
import mixin from "../../mixin";

export default {
  data: () => ({
    value_: null,
  }),
  watch: {
    value(val) {
      this.value_ = !!parseInt(val);
    },
  },
  created() {
    this.value_ = !!parseInt(this.value);
  },
  mixins: [mixin],
};
</script>
