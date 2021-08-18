<template lang="pug">
grid-item(col-end="span 2", col-end-sm="span 1")
  v-text-field(
    v-model="value_",
    :label="label",
    :name="name",
    dense,
    outlined,
    dark
  )
</template>

<script>
import mixin from "../../mixin";

export default {
  data: () => ({
    value_: null,
  }),
  mixins: [mixin],
  watch: {
    value(v) {
      this.value_ = v;
    },
  },
  mounted() {
    this.value_ = this.value;
  },
};
</script>
