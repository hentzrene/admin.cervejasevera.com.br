<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="880px"
)
  v-card.pa-4(dark)
    .title.mb-4.d-flex.flex-column.flex-sm-row.justify-space-between
      div Galeria
      div
        v-btn.mr-2.text-none(
          @click="highlight",
          color="secondary",
          :disabled="selecteds.length !== 1",
          depressed,
          small
        )
          v-icon(left, small) fas fa-star
          span Destacar
        v-btn.mr-2.text-none(
          @click="remove",
          color="secondary",
          :disabled="!selecteds.length",
          depressed,
          small
        )
          v-icon(left, small) fas fa-trash
          span Remover
        v-btn.text-none(
          @click="$refs.file.$refs.input.click()",
          color="secondary",
          depressed,
          small
        )
          v-icon(left, small) fas fa-upload
          span Enviar
    v-item-group.view-field-list.d-flex.flex-wrap(
      v-model="selecteds",
      v-if="imgs.length",
      multiple
    )
      v-item(
        v-slot="{ active, toggle }",
        v-for="({ path, id }, i) in imgs",
        :key="i",
        :value="id"
      )
        v-img.view-field-item.rounded-sm(
          v-ripple,
          @click="toggle",
          :class="active ? 'active cyan' : 'grey'",
          :src="files.replace('/admin', '') + path",
          contain
        )
          v-overlay(v-if="path === highlightedImage", absolute, opacity="0.2")
            v-icon(color="yellow", size="40") fas fa-star
    .pa-4.text-body-2.text-center.font-weight-bold(v-else) Nenhuma imagem foi enviada.
  v-overlay(v-model="uploading")
    v-progress-circular.font-weight-bold.accent--text(
      :value="progress",
      :size="120",
      :rotate="-90",
      :width="12",
      color="secondary"
    ) {{ progress }}%
  v-overlay(v-model="loading")
    v-progress-circular(:size="50", color="secondary", indeterminate)
  v-text-field.ma-0.pa-0(
    v-model="file",
    :name="inputName",
    ref="file",
    type="file",
    multiple,
    hide-details,
    hidden
  )
</template>

<script>
export default {
  props: {
    value: Boolean,
    inputName: {
      type: String,
      required: true,
    },
    highlightedImage: String,
    fieldId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    file: null,
    uploading: false,
    progress: 0,
    loading: false,
    imgs: [],
    selecteds: [],
    useDefaultUI: true,
    options: {
      // for tui-image-editor component's "options" prop
      cssMaxWidth: 700,
      cssMaxHeight: 500,
    },
  }),
  computed: {
    moduleKey() {
      return this.$rest("modules").item.key;
    },
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub;
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
        .then((imgs) => (this.imgs = imgs));
    },
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map((id) =>
          this.$rest("modulesImages").delete({
            id: id,
            params: { moduleId: this.moduleId },
          })
        )
      ).finally(() => {
        this.selecteds = [];
        this.get().then(() => (this.loading = false));
      });
    },
    highlight() {
      this.loading = true;

      const path = this.imgs.find(({ id }) => id == this.selecteds[0])["path"];
      const data = {};
      data[this.inputName] = path;

      this.$rest(this.moduleKey)
        .put({
          id: this.itemId,
          prop: this.inputName,
          data,
        })
        .then(() =>
          this.$rest(this.moduleKey)
            .get({ id: this.itemId })
            .then(() => {
              this.selecteds = [];
              this.loading = false;
            })
        );
    },
  },
  watch: {
    async file() {
      const files = this.$refs.file.$refs.input.files,
        onUploadProgress = ({ loaded, total }) =>
          (this.progress = new Intl.NumberFormat("pt-BR", {
            maximumFractionDigits: 0,
          }).format((loaded / total) * 100));

      this.uploading = true;

      for (const file of files) {
        await this.$rest("modulesImages")
          .upload({
            file,
            prop: "image",
            params: {
              itemId: this.itemId,
              moduleId: this.moduleId,
              fieldId: this.fieldId,
            },
            onUploadProgress,
          })
          .then(({ file, id }) => {
            this.progress = 0;
            this.imgs.push({ path: file, id });
          });
      }

      this.$refs.file.$refs.input.value = "";
      this.uploading = false;
    },
  },
  created() {
    this.get();
  },
};
</script>

<style scoped>
.view-field-list {
  gap: 16px;
}
.view-field-list > div {
  height: 80px;
  width: 80px;
  max-width: 80px;
  object-fit: contain;
}
.view-field-item {
  cursor: pointer;
}
.view-field-item.active {
  outline: 2px solid cyan;
  position: relative;
}
.view-field-item.active::after {
  content: "";
  display: block;
  height: 100%;
  width: 100%;
  background: cyan;
  opacity: 0.4;
}
.v-progress-circular__overlay {
  transition: none !important;
}
</style>
