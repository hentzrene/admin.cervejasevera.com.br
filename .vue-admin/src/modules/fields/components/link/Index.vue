<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  .d-flex
    v-text-field(
      :label="label",
      :value="value_",
      :name="name",
      readonly,
      dense,
      outlined,
      dark
    )
    v-btn.ml-1(@click="optionsDialog = true", icon, small)
      v-icon(small) fas fa-cog
  options(
    v-model="optionsDialog",
    :field-id="fieldId",
    :field-options="fieldOptions"
  )
</template>

<script>
import Options from "./Options";
import mixin from "../../mixin";
import { formatToURL } from "../../../../components/utils";

export default {
  props: {
    fieldId: {
      type: Number,
      required: true,
    },
    fieldOptions: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    optionsDialog: false,
  }),
  computed: {
    moduleKey() {
      return this.$rest("modules").item.key;
    },
    moduleItem() {
      return this.$rest(this.moduleKey).item;
    },
    value_() {
      const r = this.fieldOptions.pattern.replace(
        /({([^}]+)})/g,
        (match, p1, p2) => {
          return this.moduleItem[p2] || "";
        }
      );

      return location.origin + "/" + formatToURL(r);
    },
  },
  mixins: [mixin],
  components: {
    Options,
  },
};
</script>
