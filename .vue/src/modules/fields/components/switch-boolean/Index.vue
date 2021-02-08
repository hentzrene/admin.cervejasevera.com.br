<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  v-switch(
    v-model="value_",
    :label="label",
    color="accent",
    dense,
    outlined,
    dark
  )
  input(type="hidden", :name="name", :value="parseInt(value_ | 0)")
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
