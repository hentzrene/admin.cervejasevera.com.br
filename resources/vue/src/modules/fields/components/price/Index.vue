<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <v-text-field
      @beforeinput="formatInput"
      v-model="value_"
      :label="label"
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
    value_: null,
  }),
  methods: {
    formatInput(event) {
      if (event.inputType === "insertFromPaste") {
        event.preventDefault();

        const formatedInput = event.data
          .replace(/[^0-9,.]/g, "")
          .replace(/\.([0-9]{1,2})$/, ",$1")
          .replace(/\./g, "")
          .replace(/,/, ".");

        this.value_ = formatedInput;
      }
    },
  },
  mounted() {
    this.value_ = this.value;
  },
  mixins: [mixin],
};
</script>
