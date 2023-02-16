<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <div class="d-flex">
      <v-text-field
        :label="label"
        :value="value_"
        :name="name"
        readonly="readonly"
        dense="dense"
        outlined="outlined"
        dark="dark"
      ></v-text-field>
      <v-btn
        class="ml-1"
        @click="optionsDialog = true"
        icon="icon"
        small="small"
      >
        <v-icon small="small">fas fa-cog</v-icon>
      </v-btn>
    </div>
    <options
      v-model="optionsDialog"
      :field-id="fieldId"
      :field-options="fieldOptions"
    ></options>
  </grid-item>
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
    itemProp() {
      return (prop) => {
        if (prop === "id") return this.moduleItem["id"];

        const field = this.$parent.$children.find(({ name }) => name === prop);

        if (!field) return "";

        return field.value_ || "";
      };
    },
    value_() {
      const pattern = this.fieldOptions.pattern;

      if (!pattern) return;

      const r = pattern.replace(/({([^}]+)})/g, (match, p1, p2) => {
        const value = this.itemProp(p2);

        return value ? formatToURL(value) : "";
      });

      return r;
    },
  },
  mixins: [mixin],
  components: {
    Options,
  },
};
</script>
