<template>
  <template-dialog-any
    @input="(data) => $emit('input', data)"
    :value="value"
    title="Categorias"
    max-width="500px"
  >
    <template #actions>
      <template-dialog-header-button
        v-if="isAdminUser"
        @click="linkModule ? unlink() : (linkModuleDialog = true)"
        :icon="linkModule ? 'fas fa-unlink' : 'fas fa-link'"
        :text="(linkModule ? 'Desvincular' : 'Vincular') + ' Módulo'"
        :disabled="linkModule ? false : Boolean(categories.length)"
      ></template-dialog-header-button>
      <template v-if="!linkModule">
        <template-dialog-header-button
          @click="remove"
          :disabled="!selecteds.length"
          icon="fas fa-trash"
          text="Remover"
        ></template-dialog-header-button>
        <template-dialog-header-button
          @click="addDialog = true"
          icon="fas fa-plus"
          text="Adicionar"
        ></template-dialog-header-button>
      </template>
    </template>
    <div
      class="pt-8 pb-4 text-body-2 text-center font-weight-bold"
      v-if="linkModule"
    >
      Esse campo foi vinculado ao módulo de chave "{{ linkModule }}".
    </div>
    <v-data-table
      v-else-if="categories.length"
      v-model="selecteds"
      :headers="headers"
      :items="categories"
      :mobile-breakpoint="0"
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
          :return-value.sync="editCategoryTitle"
          dark="dark"
          >{{ item.title }}
          <template #input>
            <v-text-field
              :value="item.title"
              @input="(data) => (editCategoryTitle = data)"
              :rules="[rules.required]"
              label="Renomear"
              single-line="single-line"
              counter="counter"
            ></v-text-field>
          </template>
        </v-edit-dialog>
      </template>
    </v-data-table>
    <div class="pt-8 pb-4 text-body-2 text-center font-weight-bold" v-else>
      Nenhuma categoria foi adicionada.
    </div>
    <v-overlay v-if="value" v-model="loading">
      <loading></loading>
    </v-overlay>
    <add v-model="addDialog" :field-id="fieldId"></add>
    <link-module
      v-model="linkModuleDialog"
      :field-id="fieldId"
      :link-module="linkModule"
    ></link-module>
  </template-dialog-any>
</template>

<script>
import TemplateDialogAny from "../../templates/DialogAny";
import TemplateDialogHeaderButton from "../../templates/DialogHeaderButton";
import Add from "./Add";
import LinkModule from "./LinkModule";
import Loading from "@/components/tools/Loading";
import { required } from "@/components/forms/rules.js";

export default {
  props: {
    value: Boolean,
    fieldId: {
      type: Number,
      required: true,
    },
    categories: {
      type: Array,
      default: () => [],
    },
    isAdminUser: {
      type: Boolean,
      default: false,
    },
    linkModule: String,
  },
  data: () => ({
    addDialog: false,
    linkModuleDialog: false,
    loading: false,
    headers: [
      {
        text: "Título",
        value: "title",
        align: "left",
      },
    ],
    editCategoryTitle: null,
    rules: {
      required,
    },
    selecteds: [],
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
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest("modulesCategories").delete({
            id: id,
            params: { moduleId: this.moduleId },
          })
        )
      ).finally(() => {
        this.selecteds = [];
        this.loading = false;
      });
    },
    editTitle(categoryId) {
      const currentCategoryTitle = this.categories.find(
        ({ id }) => id == categoryId
      ).title;
      if (
        !this.editCategoryTitle ||
        currentCategoryTitle === this.editCategoryTitle
      )
        return;

      this.loading = true;

      this.$rest("modulesCategories")
        .put({
          id: categoryId,
          prop: "title",
          data: {
            value: this.editCategoryTitle,
          },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() => (this.loading = false));
    },
    unlink() {
      this.loading = true;

      this.$rest("modulesCategories")
        .put({
          id: "unlink-module",
          data: {
            link: null,
          },
          params: {
            fieldId: this.fieldId,
            moduleId: this.moduleId,
          },
        })
        .finally(() => {
          this.$router.go();
        });
    },
  },
  components: {
    Loading,
    Add,
    LinkModule,
    TemplateDialogAny,
    TemplateDialogHeaderButton,
  },
};
</script>
