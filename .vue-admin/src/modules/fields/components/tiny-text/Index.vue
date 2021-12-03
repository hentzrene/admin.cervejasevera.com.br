<template lang="pug">
grid-item(col-end="span 2", col-end-sm="span 1")
  .d-flex
    v-text-field(
      v-model="value_",
      :label="label",
      :name="name",
      :rules="rules",
      dense,
      outlined,
      dark
    )
    v-btn.ml-1(
      v-if="isAdminUser && fieldId",
      @click="optionsDialog = true",
      icon,
      small
    )
      v-icon(small) fas fa-cog
  options(
    v-model="optionsDialog",
    v-if="fieldId",
    :field-id="fieldId",
    :field-options="fieldOptions"
  )
</template>

<script>
import Options from "./Options";
import mixin from "../../mixin";

export default {
  props: {
    fieldId: {
      type: Number,
    },
    fieldOptions: {
      type: Object,
      default: () => ({}),
    },
  },
  data: () => ({
    optionsDialog: false,
    value_: null,
  }),
  computed: {
    rules() {
      const rules = [];

      const regexpText = this.fieldOptions.regexp;
      if (!regexpText) return rules;

      let regexp;
      regexpText.replace(/\/([^/]+)\/(.+)?/, (m, p1, p2) => {
        regexp = new RegExp(p1, p2);
      });

      const validate = (v) => !v || regexp.test(v) || "Valor inválido.";

      rules.push(validate);

      return rules;
    },
  },
  mixins: [mixin],
  watch: {
    value(v) {
      this.value_ = v;
    },
  },
  mounted() {
    this.value_ = this.value;
  },
  components: {
    Options,
  },
};
</script>
