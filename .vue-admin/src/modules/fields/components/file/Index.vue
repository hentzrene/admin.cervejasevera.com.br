<template lang="pug">
grid-item.mb-2(row-end=2, col-end=2, col-end-sm=1)
  .grey--text.text--lighten-1.font-weight-bold.text-caption {{ label }}
  tooltip(:tip="label", top)
    v-responsive.grey.d-flex.align-center.rounded-lg.primary.cursor-pointer(
      @click="dialog = true",
      :aspect-ratio="38 / 9",
      v-ripple
    )
      .d-flex.justify-center.align-center
        .d-flex.flex-column
          v-icon.d-block.mx-auto(:size="30") fas fa-file
          .text-caption.font-weight-bold.mt-1 Enviar
  list(
    v-model="dialog",
    :input-name="name",
    :highlighted-file="value",
    :field-id="fieldId"
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
    dialog: false,
  }),
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
