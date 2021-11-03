<template lang="pug">
template-dialog-any(
  @input="(data) => $emit('input', data)",
  :value="value",
  title="Galeria",
  max-width="880px"
)
  template(#actions)
    template-dialog-header-button(
      @click="order",
      icon="fas fa-save",
      text="Salvar Ordem"
    )
    template-dialog-header-button(
      @click="highlight",
      :disabled="selecteds.length !== 1",
      icon="fas fa-star",
      text="Destacar"
    )
    template-dialog-header-button(
      @click="remove",
      :disabled="!selecteds.length",
      icon="fas fa-trash",
      text="Remover"
    )
    template-dialog-header-button(
      @click="$refs.imageFileLabel.click()",
      icon="fas fa-upload",
      text="Enviar"
    )
  v-progress-linear.mb-1(
    :indeterminate="loadingOrder",
    :class="!loadingOrder && 'opacity-0'",
    color="secondary"
  )
  v-item-group.view-field-list-wrap.d-flex.flex-wrap(
    v-model="selecteds",
    v-if="imgs.length",
    multiple
  )
    draggable.view-field-list.d-flex.flex-wrap(v-model="imgs")
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
          :src="files + path + '?resize=1&h=80'",
          contain
        )
          v-overlay(v-if="path === highlightedImage", absolute, opacity="0.2")
            v-icon(color="yellow", size="40") fas fa-star
  .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-else) Nenhuma imagem foi enviada.
  v-overlay(v-model="uploading")
    v-progress-circular.font-weight-bold.accent--text(
      :value="progress",
      :size="120",
      :rotate="-90",
      :width="12",
      color="secondary"
    ) {{ progress }}%
  v-overlay(v-model="loading")
    loading
  label(:for="'imageFileInput_' + fieldId", ref="imageFileLabel")
  input.ma-0.pa-0(
    @input="upload",
    :id="'imageFileInput_' + fieldId",
    :value="file",
    ref="imageFileInput",
    type="file",
    accept="image/*",
    multiple,
    hide-details,
    hidden
  )
</template>

<script>
import TemplateDialogAny from "../../templates/DialogAny";
import TemplateDialogHeaderButton from "../../templates/DialogHeaderButton";
import Loading from "@/components/tools/Loading";
import draggable from "vuedraggable";

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
    loadingOrder: false,
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
      return this.$route.params.sub || 1;
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
            .map(({ id, path, order }) => ({
              id,
              path,
              order: parseInt(order),
            }))
            .sort((a, b) => a.order - b.order);
        });
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
    async upload({ target }) {
      const files = target.files,
        onUploadProgress = ({ loaded, total }) =>
          (this.progress = new Intl.NumberFormat("pt-BR", {
            maximumFractionDigits: 0,
          }).format((loaded / total) * 100));

      this.uploading = true;

      Promise.all(
        Array.from(files).map((file) =>
          this.$rest("modulesImages")
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
            })
        )
      ).then(() => {
        this.$refs.imageFileInput.value = "";
        this.uploading = false;
      });
    },
    order() {
      this.loadingOrder = true;

      const ordered = this.imgs.map(({ id, path }, i) => ({
        id,
        path,
        order: i,
      }));

      this.$rest("modulesImages")
        .put({
          id: "order",
          data: { images: ordered },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() => (this.loadingOrder = false));
    },
  },
  created() {
    this.get();
  },
  components: {
    Loading,
    draggable,
    TemplateDialogAny,
    TemplateDialogHeaderButton,
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
.opacity-0 {
  opacity: 0;
}
</style>
