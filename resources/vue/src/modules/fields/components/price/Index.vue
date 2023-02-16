<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <v-text-field
      :label="label"
      :value="value"
      :name="name"
      :rules="[rules.price]"
      append-icon="fas fa-dollar-sign"
      type="number"
      step=".01"
      min="0.00"
      max="9999999999.99"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
  </grid-item>
</template>

<script>
import mixin from "../../mixin";
import { price } from "@/components/forms/rules";

export default {
  data: () => ({
    rules: {
      price,
    },
  }),

  mixins: [mixin],
};
</script>
