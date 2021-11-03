<template lang="pug">
grid-item.field-datetime(col-end="span 2", col-end-sm="span 1")
  v-text-field(
    :label="label",
    :value="_value",
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
  mixins: [mixin],
  computed: {
    moduleKey() {
      return this.$rest("modules").item.key;
    },
    item() {
      return this.$rest(this.moduleKey).item;
    },
    _value() {
      if (this.value) return toDateTimeHTML(this.value);
      else if (this.item.alteredAt) return null;
      else return toDateTimeHTML(new Date());
    },
  },
};
</script>

<style>
.field-datetime
  input[type="datetime-local" i]::-webkit-calendar-picker-indicator {
  filter: brightness(1) invert(1);
}
</style>
