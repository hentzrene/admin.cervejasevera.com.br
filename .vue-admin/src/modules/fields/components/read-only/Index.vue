<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  v-text-field(
    :label="label",
    :value="value",
    readonly
    dense,
    outlined,
    dark
  )
</template>

<script>
import mixin from "../../mixin";

export default {
  mixins: [mixin],
};
</script>
