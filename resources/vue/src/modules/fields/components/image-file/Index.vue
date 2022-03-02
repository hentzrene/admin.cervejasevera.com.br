<template lang="pug">
grid-item.mb-2(row-end="span 4", col-end="span 2", col-end-sm="span 1")
  .grey--text.text--lighten-1.font-weight-bold.text-caption {{ label }}
  tooltip(:tip="label", top)
    v-img.cursor-pointer.grey.d-flex.align-center.rounded-lg(
      @click="dialog = true",
      :src="files.replace(prefixPath, '') + (featuredImage && featuredImage.path)",
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
    :images="imgs",
    :featured-image="featuredImage",
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
    imgs: [],
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub || 1;
    },
    featuredImage() {
      return this.imgs.find((img) => img.id == this.value);
    },
  },
  methods: {
    get() {
      return this.$rest("modulesImages")
        .get({
          id: this.itemId,
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
          },
        })
        .then((imgs) => {
          this.imgs = imgs
            .map(({ id, path, title, order }) => ({
              id,
              path,
              title,
              order: parseInt(order),
            }))
            .sort((a, b) => a.order - b.order);
        });
    },
  },
  mounted() {
    this.get();
  },
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
