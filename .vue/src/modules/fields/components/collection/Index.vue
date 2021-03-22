<template lang="pug">
grid-item.mb-2(row-end=2, col-end=2, col-end-sm=1)
  .grey--text.text--lighten-1.font-weight-bold.text-caption.d-flex.align-center.mb-1
    span {{ label }}
    v-btn.ml-1(@click="configurationDialog = true", icon, x-small)
      v-icon(small) fas fa-cog
  tooltip(:tip="label", top)
    v-responsive.grey.d-flex.align-center.rounded-lg.primary.cursor-pointer(
      @click="listDialog = true",
      :aspect-ratio="38 / 9",
      v-ripple
    )
      .d-flex.justify-center.align-center
        .d-flex.flex-column
          v-icon.d-block.mx-auto(:size="30") fas fa-layer-group
          .text-caption.font-weight-bold.mt-1 Alterar
  list(
    v-model="listDialog",
    @saved="listDialog = false",
    :items="parsedValue",
    :input-name="name",
    :items-field-type="fieldOptions.itemsType"
  )
  configuration(
    v-model="configurationDialog",
    :module-id="fieldId",
    :field-id="fieldId",
    :field-options="fieldOptions"
  )
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import List from "./List";
import Configuration from "./Configuration";
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
    listDialog: false,
    configurationDialog: false,
  }),
  computed: {
    moduleId() {
      return parseInt(this.$rest("modules").item.id);
    },
    parsedValue() {
      return JSON.parse(this.value || "[]");
    },
  },
  beforeCreate() {
    this.$rest("modulesFieldsTypes").get();
  },
  mixins: [mixin],
  components: {
    Tooltip,
    List,
    Configuration,
  },
};
</script>
