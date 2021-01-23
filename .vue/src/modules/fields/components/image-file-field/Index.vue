<template lang="pug">
grid-item.mb-2(row-end=4, col-end=2, col-end-sm=1)
  .grey--text.text--lighten-1.font-weight-bold.text-caption.d-flex.align-center.mb-1
    span {{ label }}
    v-btn.ml-1(icon, x-small)
      v-icon(small) fas fa-cog
  tooltip(:tip="label", top)
    v-img.cursor-pointer.grey.d-flex.align-center.rounded-lg(
      @click="dialog = true",
      :src="files.replace('/admin', '') + value",
      :aspect-ratio="19 / 9",
      v-ripple,
      contain
    )
      .d-flex.justify-center.align-center
        v-sheet.d-flex.flex-column.justify-center.align-center.primary.rounded-circle(
          height="80",
          width="80"
        )
          v-icon.d-block.mx-auto(:size="30") fas fa-file-image
          .text-caption.font-weight-bold.mt-1 Enviar
  list(
    v-model="dialog",
    :input-name="name",
    :highlighted-image="value",
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
