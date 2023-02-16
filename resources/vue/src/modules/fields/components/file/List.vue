<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="880px"
  >
    <v-card class="pa-4" dark="dark">
      <div
        class="title mb-4 d-flex flex-column flex-sm-row justify-space-between"
      >
        <div>Arquivos</div>
        <div>
          <v-btn
            class="mr-2 text-none"
            @click="remove"
            color="secondary"
            :disabled="!selecteds.length"
            depressed="depressed"
            small="small"
          >
            <v-icon left="left" small="small">fas fa-trash</v-icon
            ><span>Remover</span>
          </v-btn>
          <v-btn
            class="text-none"
            @click="$refs.fileLabel.click()"
            color="secondary"
            depressed="depressed"
            small="small"
          >
            <v-icon left="left" small="small">fas fa-upload</v-icon
            ><span>Enviar</span>
          </v-btn>
        </div>
      </div>
      <v-data-table
        :headers="headers"
        :items="files"
        :mobile-breakpoint="0"
        v-if="files.length"
        v-model="selecteds"
        no-data-text="Não há registros."
        sort-by="id"
        sort-desc="sort-desc"
        show-select="show-select"
        disable-pagination="disable-pagination"
        hide-default-footer="hide-default-footer"
      >
        <template #item.title="{ item }">
          <v-edit-dialog
            class="primary lighten-4"
            @save="editTitle(item.id)"
            :return-value.sync="editFileTitle"
            dark="dark"
            >{{ item.title }}
            <template #input>
              <v-text-field
                :value="item.title"
                @input="(data) => (editFileTitle = data)"
                :rules="[rules.required]"
                label="Renomear"
                single-line="single-line"
                counter="counter"
              ></v-text-field>
            </template>
          </v-edit-dialog>
        </template>
        <template #item.action="{ item }">
          <v-btn
            v-if="item.path === highlightedFile"
            @click="highlight(item.id)"
            color="yellow"
            icon="icon"
            small="small"
          >
            <v-icon small="small">fas fa-star</v-icon>
          </v-btn>
          <v-btn
            v-else
            @click="highlight(item.id)"
            color="yellow"
            icon="icon"
            small="small"
          >
            <v-icon small="small">far fa-star</v-icon>
          </v-btn>
        </template>
      </v-data-table>
      <div class="pt-8 pb-4 text-body-2 text-center font-weight-bold" v-else>
        Nenhum arquivo foi enviado.
      </div>
    </v-card>
    <v-overlay v-model="uploading">
      <v-progress-circular
        class="font-weight-bold accent--text"
        :value="progress"
        :size="120"
        :rotate="-90"
        :width="12"
        color="secondary"
        >{{ progress }}%</v-progress-circular
      >
    </v-overlay>
    <v-overlay v-model="loading">
      <loading></loading>
    </v-overlay>
    <label :for="'fileInput_' + fieldId" ref="fileLabel"></label>
    <input
      class="ma-0 pa-0"
      @input="upload"
      :id="'fileInput_' + fieldId"
      :value="file"
      ref="fileInput"
      type="file"
      multiple="multiple"
      hide-details="hide-details"
      hidden="hidden"
    />
  </v-dialog>
</template>

<script>
import Loading from "@/components/tools/Loading";
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
      return this.$route.params.sub || 1;
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
    async upload({ target }) {
      const files = target.files,
        onUploadProgress = ({ loaded, total }) =>
          (this.progress = new Intl.NumberFormat("pt-BR", {
            maximumFractionDigits: 0,
          }).format((loaded / total) * 100));

      this.uploading = true;

      try {
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
            })
            .finally(() => (this.uploading = false));
        }

        this.uploading = false;
      } catch (e) {
        console.error(e);
        this.uploading = false;
      }

      this.$refs.fileInput.value = "";
    },
  },
  created() {
    this.get();
  },
  components: {
    Loading,
  },
};
</script>

<style>
.v-progress-circular__overlay {
  transition: none !important;
}
</style>
