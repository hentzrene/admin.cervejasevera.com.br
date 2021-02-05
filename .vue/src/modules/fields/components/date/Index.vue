<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  v-text-field(
    :label="label",
    :value="value | toDateTimeHTML",
    :name="name",
    type="datetime-local",
    dense,
    outlined,
    dark
  )
</template>

<script>
import mixin from "../../mixin";
import { toDateTimeHTML } from "@/components/filters.js";

export default {
  filters: {
    toDateTimeHTML: (str) => toDateTimeHTML(new Date(str || null)),
  },
  mixins: [mixin],
};
</script>
