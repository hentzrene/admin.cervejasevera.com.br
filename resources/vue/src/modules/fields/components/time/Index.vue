<template>
  <grid-item class="field-time" col-end="span 2" col-end-sm="span 1">
    <v-text-field
      :label="label"
      :value="_value"
      :name="name"
      type="time"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
  </grid-item>
</template>

<script>
import mixin from "../../mixin";
import { toTime } from "@/components/filters.js";

export default {
  mixins: [mixin],
  computed: {
    moduleKey() {
      return this.$route.params.module;
    },
    item() {
      return this.$rest(this.moduleKey).item;
    },
    _value() {
      if (this.value) return this.value;
      else if (this.item.alteredAt) return null;
      else return toTime(new Date());
    },
  },
};
</script>

<style>
.field-time input[type="time" i]::-webkit-calendar-picker-indicator {
  filter: brightness(1) invert(1);
}
</style>
