<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <div class="d-flex">
      <v-select
        v-model="value_"
        :label="label"
        :name="name"
        :items="subcategoriesToSelect"
        dense="dense"
        outlined="outlined"
        dark="dark"
      ></v-select>
      <v-btn class="ml-1" @click="listDialog = true" icon="icon" small="small">
        <v-icon small="small">fas fa-cog</v-icon>
      </v-btn>
    </div>
    <list
      v-model="listDialog"
      :module-id="moduleId"
      :field-id="fieldId"
      :items="allSubcategoriesField"
      :field-category="linkedCategoryId"
    ></list>
  </grid-item>
</template>

<script>
import List from "./list/Index";
import mixin from "../../mixin";

export default {
  mixins: [mixin],
  props: {
    fieldId: {
      type: Number,
      required: true,
    },
    fieldOptions: {
      type: Object,
      required: true,
    },
    moduleId: {
      type: [String, Number],
      required: true,
    },
    moduleKey: {
      type: String,
      required: true,
    },
  },
  data: () => ({
    listDialog: false,
    value_: null,
  }),
  computed: {
    allSubcategoriesField() {
      return this.$rest("modulesSubcategories").getters.filterByProperty(
        "fieldId",
        this.fieldId
      );
    },
    linkedCategoryId() {
      return parseInt(this.fieldOptions.category);
    },
    linkedCategoryFieldComponent() {
      const linkedCategoryField = this.$rest("modules").item.fields.find(
        ({ id }) => id == this.linkedCategoryId
      );

      if (!linkedCategoryField) {
        return null;
      }

      return this.$parent.$children.find(
        ({ name }) => name === linkedCategoryField.key
      );
    },
    currentLinkedCategoryValue() {
      if (!this.linkedCategoryFieldComponent) {
        return null;
      }

      return this.linkedCategoryFieldComponent.value_;
    },
    savedLinkedCategoryValue() {
      if (!this.linkedCategoryFieldComponent) {
        return null;
      }

      return this.linkedCategoryFieldComponent.value;
    },
    subcategoriesFromLinkedCategory() {
      if (!this.currentLinkedCategoryValue) return [];

      return this.allSubcategoriesField.filter(
        ({ categoryId }) => categoryId == this.currentLinkedCategoryValue
      );
    },
    subcategoriesToSelect() {
      const items = this.subcategoriesFromLinkedCategory.map(
        ({ id, title }) => ({
          text: title,
          value: id,
        })
      );

      items.unshift({
        text: "",
        value: "",
      });

      return items;
    },
  },
  watch: {
    value(val) {
      this.value_ = val;
    },
    currentLinkedCategoryValue(val) {
      if (val !== this.savedLinkedCategoryValue) {
        this.value_ = null;
      } else {
        this.value_ = this.value;
      }
    },
  },
  mounted() {
    this.value_ = this.value;
  },
  created() {
    this.$rest("modulesSubcategories").get({
      params: {
        moduleId: this.moduleId,
        fieldId: this.fieldId,
      },
      keepCache: true,
    });
  },
  components: {
    List,
  },
};
</script>
