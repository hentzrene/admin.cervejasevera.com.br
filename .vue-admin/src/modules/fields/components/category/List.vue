<template lang="pug">
template-dialog-any(
  @input="(data) => $emit('input', data)",
  :value="value",
  title="Categorias",
  max-width="500px"
)
  template(#actions)
    template-dialog-header-button(
      @click="linkModule ? unlink() : (linkModuleDialog = true)",
      :icon="linkModule ? 'fas fa-unlink' : 'fas fa-link'",
      :text="(linkModule ? 'Desvincular' : 'Vincular') + ' Módulo'",
      :disabled="linkModule ? false : Boolean(categories.length)"
    )
    template(v-if="!linkModule")
      template-dialog-header-button(
        @click="remove",
        :disabled="!selecteds.length",
        icon="fas fa-trash",
        text="Remover"
      )
      template-dialog-header-button(
        @click="addDialog = true",
        icon="fas fa-plus",
        text="Adicionar"
      )
  .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-if="linkModule") Esse campo foi vinculado ao módulo de chave "{{ linkModule }}".
  v-data-table(
    v-else-if="categories.length",
    v-model="selecteds",
    :headers="headers",
    :items="categories",
    :mobile-breakpoint="0",
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
        :return-value.sync="editCategoryTitle",
        dark
      ) {{ item.title }}
        template(#input)
          v-text-field(
            :value="item.title",
            @input="(data) => (editCategoryTitle = data)",
            :rules="[rules.required]",
            label="Renomear",
            single-line,
            counter
          )
  .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-else) Nenhuma categoria foi adicionada.
  v-overlay(v-if="value", v-model="loading")
    loading
  add(v-model="addDialog", :field-id="fieldId")
  link-module(
    v-model="linkModuleDialog",
    :field-id="fieldId",
    :link-module="linkModule"
  )
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
