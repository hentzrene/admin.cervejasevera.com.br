<template lang="pug">
template-dialog-any(
  @input="(data) => $emit('input', data)",
  :value="value",
  title="Subcategorias",
  max-width="500px"
)
  template(#actions)
    template-dialog-header-button(
      @click="removeItem",
      :disabled="!tableSelecteds.length",
      icon="fas fa-trash",
      text="Remover"
    ) Remover
    template-dialog-header-button(
      @click="addDialog = true",
      icon="fas fa-plus",
      text="Adicionar"
    )
  v-row
    v-col(cols=12, sm=6)
      select-field-category(
        :value="fieldCategory",
        :field-id="fieldId",
        :module-id="moduleId",
        :items="fieldsCategory"
      )
    v-col(cols=12, sm=6)
      v-select(
        v-model="selectedCategory",
        :items="categories",
        label="Campo",
        outlined,
        dense,
        hide-details
      )
  v-data-table(
    v-if="items.length",
    v-model="tableSelecteds",
    :headers="tableHeaders",
    :items="items",
    :mobile-breakpoint="0",
    :loading="loading",
    :search="selectedCategory",
    :custom-filter="filterItems",
    no-data-text="Não há registros.",
    no-results-text="Não há registros.",
    sort-by="id",
    sort-desc,
    show-select,
    disable-pagination,
    hide-default-footer
  )
    template(#item.title="{ item }")
      v-edit-dialog.primary.lighten-4(
        @save="updateItemTitle(item.id)",
        :return-value.sync="itemTitle",
        dark
      ) {{ item.title }}
        template(#input)
          v-text-field(
            :value="item.title",
            @input="(data) => (itemTitle = data)",
            :rules="[rules.required]",
            label="Renomear",
            single-line,
            counter
          )
  .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-else) Nenhuma categoria foi adicionada.
  add(
    v-model="addDialog",
    :field-id="fieldId",
    :module-id="moduleId",
    :categories="categories"
  )
</template>

<script>
import TemplateDialogAny from "../../../templates/DialogAny";
import TemplateDialogHeaderButton from "../../../templates/DialogHeaderButton";
import SelectFieldCategory from "./SelectFieldCategory";
import Add from "./Add";
import { required } from "@/components/forms/rules.js";

export default {
  props: {
    value: Boolean,
    moduleId: {
      type: Number,
      required: true,
    },
    fieldId: {
      type: Number,
      required: true,
    },
    fieldCategory: {
      type: Number,
      required: true,
    },
    items: {
      type: Array,
      default: () => [],
    },
  },
  data: () => ({
    addDialog: false,
    loading: false,
    tableSelecteds: [],
    tableHeaders: [
      {
        text: "Título",
        value: "title",
        align: "left",
      },
    ],
    itemTitle: null,
    rules: {
      required,
    },
    selectedCategory: null,
  }),
  computed: {
    fieldsCategory() {
      const fieldsCategory = this.$rest("modules")
        .item.fields.filter(({ typeKey }) => typeKey === "category")
        .map(({ id, name }) => ({ text: name, value: parseInt(id) }));

      fieldsCategory.unshift({ text: "", value: "" });

      return fieldsCategory;
    },
    categories() {
      return this.$rest("modulesCategories")
        .getters.filterByProperty("fieldId", this.fieldCategory)
        .map(({ id, title }) => ({ text: title, value: id }));
    },
  },
  methods: {
    removeItem() {
      this.loading = true;
      Promise.all(
        this.tableSelecteds.map(({ id }) =>
          this.$rest("modulesSubcategories").delete({
            id: id,
            params: { moduleId: this.moduleId },
          })
        )
      ).finally(() => {
        this.tableSelecteds = [];
        this.loading = false;
      });
    },
    updateItemTitle(subcategoryId) {
      const currentItemTitle = this.items.find(
        ({ id }) => id == subcategoryId
      ).title;
      if (!this.itemTitle || currentItemTitle === this.itemTitle) return;

      this.loading = true;

      this.$rest("modulesSubcategories")
        .put({
          id: subcategoryId,
          prop: "title",
          data: {
            value: this.itemTitle,
          },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() => this.$parent.get().then(() => (this.loading = false)));
    },
    filterItems(value, search, { categoryId }) {
      return categoryId === search;
    },
  },
  components: {
    TemplateDialogAny,
    TemplateDialogHeaderButton,
    SelectFieldCategory,
    Add,
  },
};
</script>
