<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="880px"
)
  v-card.pa-4(dark)
    .title.mb-4.d-flex.flex-column.flex-sm-row.justify-space-between
      div Arquivos
      div
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
    v-data-table(
      :headers="headers",
      :items="files",
      :mobile-breakpoint="0",
      v-if="files.length",
      v-model="selecteds",
      no-data-text="Não há registros.",
      sort-by="id",
      sort-desc,
      show-select,
      disable-pagination,
      hide-default-footer
    )
      template(#item.title="{ item }")
        v-edit-dialog.primary.lighten-4(
          @save="editTitle(item.id)",
          :return-value.sync="editFileTitle",
          dark
        ) {{ item.title }}
          template(#input)
            v-text-field(
              :value="item.title",
              @input="(data) => (editFileTitle = data)",
              :rules="[rules.required]",
              label="Renomear",
              single-line,
              counter
            )
      template(#item.action="{ item }")
        v-btn(
          v-if="item.path === highlightedFile",
          @click="highlight(item.id)",
          color="yellow",
          icon,
          small
        )
          v-icon(small) fas fa-star
        v-btn(v-else, @click="highlight(item.id)", color="yellow", icon, small)
          v-icon(small) far fa-star
    .pa-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum arquivo foi enviado.
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
import { required } from "@/components/forms/rules.js";

export default {
  props: {
    value: Boolean,
    inputName: {
      type: String,
      required: true,
    },
    highlightedFile: String,
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
    files: [],
    selecteds: [],
    headers: [
      {
        text: "Título",
        value: "title",
        align: "left",
      },
      {
        text: "",
        value: "action",
        align: "right",
        sortable: false,
      },
    ],
    editFileTitle: null,
    rules: {
      required,
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
      return this.$rest("modulesFiles")
        .get({
          id: this.itemId,
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
          },
        })
        .then((files) => (this.files = files));
    },
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest("modulesFiles").delete({
            id: id,
            params: { moduleId: this.moduleId },
          })
        )
      ).finally(() => {
        this.selecteds = [];
        this.get().then(() => (this.loading = false));
      });
    },
    highlight(fileId) {
      this.loading = true;

      const path = this.files.find(({ id }) => id == fileId)["path"];
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
    editTitle(fileId) {
      const currentFileTitle = this.files.find(({ id }) => id == fileId).title;
      if (!this.editFileTitle || currentFileTitle === this.editFileTitle)
        return;

      this.loading = true;

      this.$rest("modulesFiles")
        .put({
          id: fileId,
          prop: "title",
          data: {
            value: this.editFileTitle,
          },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() =>
          this.get().then(() => {
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
        await this.$rest("modulesFiles")
          .upload({
            file,
            prop: "file",
            data: {
              title: file.name,
            },
            params: {
              itemId: this.itemId,
              moduleId: this.moduleId,
              fieldId: this.fieldId,
            },
            onUploadProgress,
          })
          .then(({ file, title, id }) => {
            this.progress = 0;
            this.files.push({ path: file, title, id });
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

<style>
.v-progress-circular__overlay {
  transition: none !important;
}
</style>
