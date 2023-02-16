<template>
  <grid-item class="field-date" col-end="span 2" col-end-sm="span 1">
    <v-text-field
      :label="label"
      :value="_value"
      :name="name"
      type="date"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
  </grid-item>
</template>

<script>
import mixin from "../../mixin";
import { toDateHTML } from "@/components/filters.js";

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
      if (this.value) return this.value;
      else if (this.item.alteredAt) return null;
      else return toDateHTML(new Date());
    },
  },
};
</script>

<style>
.field-date input[type="date" i]::-webkit-calendar-picker-indicator {
  filter: brightness(1) invert(1);
}
</style>
