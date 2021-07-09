<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  .d-flex
    v-select(
      v-model="value_",
      :label="label",
      :name="name",
      :items="itemsSelect",
      dense,
      outlined,
      dark
    )
    v-btn.ml-1(@click="listDialog = true", icon, small)
      v-icon(small) fas fa-cog
  list(
    v-model="listDialog",
    :module-id="moduleId",
    :field-id="fieldId",
    :items="items",
    :field-category="parseInt(fieldOptions.category)"
  )
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import List from "./list/Index";
import mixin from "../../mixin";

export default {
  props: {
    fieldId: {
      type: Number,
      required: true,
    },
    fieldOptions: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    listDialog: false,
    value_: null,
  }),
  computed: {
    moduleKey() {
      return this.$route.params.module;
    },
    moduleId() {
      return parseInt(this.$rest("modules").item.id);
    },
    savedSelectedCategory() {
      if (!this.fieldOptions.category) return null;

      const categoryField = this.$rest("modules").item.fields.find(
        ({ id }) => id == this.fieldOptions.category
      );

      if (!categoryField) return null;

      return this.$parent.$children.find(
        ({ name }) => name === categoryField.key
      ).value;
    },
    selectedCategory() {
      if (!this.fieldOptions.category) return null;

      const categoryField = this.$rest("modules").item.fields.find(
        ({ id }) => id == this.fieldOptions.category
      );

      if (!categoryField) return null;

      return this.$parent.$children.find(
        ({ name }) => name === categoryField.key
      ).value_;
    },
    items() {
      if (!this.selectedCategory) return [];

      return this.$rest("modulesSubcategories")
        .getters.filterByProperty("fieldId", this.fieldId)
        .filter(({ categoryId }) => categoryId == this.selectedCategory);
    },
    itemsSelect() {
      const items = this.items.map(({ title, id }) => ({
        text: title,
        value: id,
      }));

      items.unshift({
        text: "",
        value: "",
      });

      return items;
    },
  },
  watch: {
    value(v) {
      this.value_ = v;
    },
    selectedCategory(v) {
      if (v !== this.savedSelectedCategory) this.value_ = null;
      else this.value_ = this.value;
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
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
