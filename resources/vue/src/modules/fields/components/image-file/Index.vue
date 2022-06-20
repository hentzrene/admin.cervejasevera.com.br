<template lang="pug">
grid-item.mb-2(row-end="span 4", col-end="span 2", col-end-sm="span 1")
  .grey--text.text--lighten-1.font-weight-bold.text-caption {{ label }}
  v-img.grey.rounded-lg(
    :src="files.replace(prefixPath, '') + (featuredImage && featuredImage.path)",
    :aspect-ratio="19 / 9",
    :class="(!featuredImage || !featuredImage.path) && 'd-flex align-center'"
    contain
  )
    template(v-if="!loading")
      .d-flex.justify-center.align-center(v-if="!featuredImage || !featuredImage.path")
        v-sheet.d-flex.flex-column.justify-center.align-center.primary.rounded-circle.cursor-pointer(
          @click="dialog = true",
          v-ripple
          height="80",
          width="80"
        )
          v-icon.d-block.mx-auto(:size="30") fas fa-file-image
          .text-caption.font-weight-bold.mt-1 Enviar
      div.d-flex.justify-end.ma-3(v-else)
        v-btn.text-none(
          :href="files.replace(prefixPath, '') + featuredImage.path"
          target="_blank"
          download="image"
          depressed
          small
        )
          v-icon(x-small left) fas fa-download
          | Download
        v-btn.ml-2.text-none(
          @click="dialog = true",
          target="_blank"
          download="image"
          depressed
          small
        )
          v-icon(x-small left) fas fa-file-image
          | Alterar
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
    loading: true,
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
        })
        .finally(() => (this.loading = false));
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
