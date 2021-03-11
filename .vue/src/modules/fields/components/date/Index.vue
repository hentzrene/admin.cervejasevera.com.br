<template lang="pug">
grid-item.field-date(col-end=2, col-end-sm=1)
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
    toDateTimeHTML: (str) => str && toDateTimeHTML(new Date(Date.now())),
  },
  mixins: [mixin],
};
</script>

<style>
.field-date input[type="datetime-local" i]::-webkit-calendar-picker-indicator {
  filter: brightness(1) invert(1);
}
</style>
