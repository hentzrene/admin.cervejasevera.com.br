<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <v-text-field
      v-model="showValue"
      v-mask="[
        'R$ #,##',
        'R$ ##,##',
        'R$ ###,##',
        'R$ ####,##',
        'R$ #####,##',
        'R$ ######,##',
        'R$ #######,##',
        'R$ ########,##',
        'R$ #########,##',
        'R$ ##########,##',
        'R$ ###########,##',
        'R$ ############,##',
      ]"
      :label="label"
      dense
      outlined
      dark
    />
    <input :name="name" :value="value_" type="hidden" />
  </grid-item>
</template>

<script>
import mixin from "../../mixin";
import { mask } from "vue-the-mask";

export default {
  mixins: [mixin],
  data: () => ({
    showValue: null,
  }),
  computed: {
    value_() {
      return this.toFormValue(this.showValue);
    },
  },
  methods: {
    toFormValue(val) {
      if (!val) return "";

      return val.replace("R$ ", "").replace(",", ".");
    },
    toShowValue(val) {
      if (!val) return "";

      return "R$ " + val.replace(".", ",");
    },
  },
  watch: {
    value(val) {
      this.showValue = this.toShowValue(val);
    },
  },
  directives: { mask },
  mounted() {
    this.showValue = this.toShowValue(this.value);
  },
};
</script>
