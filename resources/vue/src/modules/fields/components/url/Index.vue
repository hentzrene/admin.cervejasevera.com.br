<template lang="pug">
grid-item(col-end="span 2", col-end-sm="span 1")
  .d-flex
    v-text-field(
      :label="label",
      v-model="value_",
      :rules="[url]",
      :prefix="fieldOptions.prefix",
      append-icon="fas fa-globe",
      type="url",
      dense,
      outlined,
      dark
    )
    input(
      type="hidden",
      :name="name",
      :value="prefix ? prefix + value_ : value_"
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
import { url } from "@/components/forms/rules";
import Options from "./Options";
import mixin from "../../mixin";

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
    value_: null,
  }),
  computed: {
    prefix() {
      return this.fieldOptions.prefix;
    },
    moduleKey() {
      return this.$rest("modules").item.key;
    },
  },
  methods: {
    url(v) {
      return url(this.prefix ? this.prefix + v : v);
    },
  },
  watch: {
    value(v) {
      this.value_ = this.prefix ? v.replace(this.prefix, "") : v;
    },
  },
  mounted() {
    this.value_ = this.prefix
      ? this.value.replace(this.prefix, "")
      : this.value;
  },
  mixins: [mixin],
  components: {
    Options,
  },
};
</script>
