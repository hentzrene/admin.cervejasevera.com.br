<template lang="pug">
grid-item.mb-2(row-end="span 2", col-end="span 2", col-end-sm="span 1")
  .grey--text.text--lighten-1.font-weight-bold.text-caption.d-flex.align-center.mb-1 {{ label }}
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
    :input-name="name"
  )
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import List from "./List";
import mixin from "../../mixin";

export default {
  props: {
    fieldId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    listDialog: false,
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
  },
};
</script>
