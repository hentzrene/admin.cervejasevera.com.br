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
import Mexp from "math-expression-evaluator";

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

        return field.value_ || field.value || "";
      };
    },
    value_() {
      const pattern = this.fieldOptions.pattern;

      if (!pattern) return;

      const replacedPattern = pattern.replace(
        /({([^}]+)})/g,
        (match, p1, p2) => {
          return this.itemProp(p2);
        }
      );

      const mexp = new Mexp();

      const replacedMath = replacedPattern.replace(
        /(math\[([^\]]*)\])/,
        (match, p1, p2) => {
          try {
            return mexp.eval(p2);
          } catch {
            return "";
          }
        }
      );

      const replacePrice = replacedMath.replace(
        /(currency\[([^\]]*)\])/,
        (match, p1, p2) => {
          if (!p2) {
            return "";
          }

          try {
            return new Intl.NumberFormat("pt-BR", {
              style: "currency",
              currency: "BRL",
            }).format(parseFloat(p2));
          } catch {
            return "";
          }
        }
      );

      const replacedZeroFill = replacePrice.replace(
        /(zerofill\[([^\]]*);([0-9]+)])/,
        (match, p1, p2, p3) => {
          if (!p2 || !p3) {
            return "";
          }

          try {
            return p2.padStart(+p3, "0");
          } catch {
            return "";
          }
        }
      );

      const replacedURL = replacedZeroFill.replace(
        /(url\[([^\]]*)\])/,
        (match, p1, p2) => {
          try {
            return formatToURL(p2);
          } catch {
            return "";
          }
        }
      );

      return replacedURL;
    },
  },
  mixins: [mixin],
  components: {
    Options,
  },
};
</script>
