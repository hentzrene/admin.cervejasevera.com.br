<template lang="pug">
grid-item(col-end=2)
  v-textarea(
    :label="label",
    :value="value",
    :name="name",
    counter="300",
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
