<template lang="pug">
grid-item.field-source-code(col-end="span 2")
  v-textarea.rounded-0(
    :label="label",
    :value="value",
    :name="name",
    :rows="10",
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

<style>
.field-source-code textarea {
  font-family: monospace;
  line-height: 18px !important;
}
.field-source-code .v-input__slot {
  background-color: black !important;
}
</style>
