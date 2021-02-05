<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  .d-flex
    v-select(
      :label="label",
      :value="value",
      :name="name",
      :items="categoriesSelect",
      dense,
      outlined,
      dark
    )
    v-btn.ml-1(@click="listDialog = true", icon, small)
      v-icon(small) fas fa-cog
  list(v-model="listDialog", :field-id="fieldId", :categories="categories")
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import List from "./List";
import mixin from "../../mixin";

export default {
  props: {
    fieldId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    listDialog: false,
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub;
    },
    categories() {
      return this.$rest("modulesCategories").getters.filterByProperty(
        "fieldId",
        this.fieldId
      );
    },
    categoriesSelect() {
      return this.categories.map(({ title, id }) => ({
        text: title,
        value: id,
      }));
    },
  },
  created() {
    this.$rest("modulesCategories").get({
      params: {
        moduleId: this.moduleId,
        fieldId: this.fieldId,
      },
    });
  },
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
