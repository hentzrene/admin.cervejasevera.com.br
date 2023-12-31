<template>
  <template-dialog-any
    @input="(data) => $emit('input', data)"
    :value="value"
    title="Subcategorias"
    max-width="500px"
  >
    <template #actions>
      <TemplateDialogHeaderButton
        @click="removeItem"
        :disabled="!tableSelecteds.length"
        icon="fas fa-trash"
        text="Remover"
      />
      <TemplateDialogHeaderButton
        @click="addDialog = true"
        icon="fas fa-plus"
        text="Adicionar"
      />
    </template>
    <v-row>
      <v-col cols="12" sm="6">
        <SelectFieldCategory
          @input="(val) => (fieldCategory_ = val)"
          :value="fieldCategory"
          :field-id="fieldId"
          :module-id="moduleId"
          :items="categoryTypeFieldsToSelect"
        />
      </v-col>
      <v-col cols="12" sm="6">
        <v-select
          v-model="selectedCategory"
          :items="categoriesFromCurrentCategoryTypeField"
          label="Campo"
          outlined="outlined"
          dense="dense"
          hide-details="hide-details"
        ></v-select>
      </v-col>
    </v-row>
    <v-data-table
      v-if="items.length && selectedCategory"
      v-model="tableSelecteds"
      :headers="tableHeaders"
      :items="items"
      :mobile-breakpoint="0"
      :loading="loading"
      :search="selectedCategory"
      :custom-filter="filterItems"
      no-data-text="Não há registros."
      no-results-text="Não há registros."
      sort-by="id"
      sort-desc="sort-desc"
      show-select="show-select"
      disable-pagination="disable-pagination"
      hide-default-footer="hide-default-footer"
    >
      <template #item.title="{ item }">
        <v-edit-dialog
          class="primary lighten-4"
          @save="updateItemTitle(item.id)"
          :return-value.sync="itemTitle"
          dark="dark"
          >{{ item.title }}
          <template #input>
            <v-text-field
              :value="item.title"
              @input="(data) => (itemTitle = data)"
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
      Nenhuma categoria disponível .
    </div>
    <add
      v-model="addDialog"
      :field-id="fieldId"
      :module-id="moduleId"
      :categories="categoriesFromCurrentCategoryTypeField"
    ></add>
  </template-dialog-any>
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
      type: [String, Number],
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
    fieldCategory_: null,
    selectedCategory: null,
  }),
  computed: {
    categoryTypeFields() {
      return this.$rest("modules").item.fields.filter(
        ({ typeKey }) => typeKey === "category"
      );
    },
    categoryTypeFieldsToSelect() {
      const categoryTypeFieldsToSelect = this.categoryTypeFields.map(
        ({ id, name }) => ({
          text: name,
          value: parseInt(id),
        })
      );

      categoryTypeFieldsToSelect.unshift({ text: "", value: "" });

      return categoryTypeFieldsToSelect;
    },
    categoriesFromCurrentCategoryTypeField() {
      const categoriesFromCurrentCategoryTypeField = this.$rest(
        "modulesCategories"
      )
        .getters.filterByProperty("fieldId", this.fieldCategory_)
        .map(({ id, title }) => ({ text: title, value: id }));

      categoriesFromCurrentCategoryTypeField.unshift({ text: "", value: "" });

      return categoriesFromCurrentCategoryTypeField;
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
    filterItems(value, search, item) {
      return item.categoryId == search;
    },
  },
  watch: {
    fieldCategory_() {
      this.selectedCategory = null;
    },
  },
  mounted() {
    this.fieldCategory_ = this.fieldCategory;
  },
  components: {
    TemplateDialogAny,
    TemplateDialogHeaderButton,
    SelectFieldCategory,
    Add,
  },
};
</script>
