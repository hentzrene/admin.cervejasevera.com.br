<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  .d-flex
    v-select(
      v-model="value_",
      :label="label",
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
    value_: null,
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
      const categories = this.categories.map(({ title, id }) => ({
        text: title,
        value: id,
      }));

      categories.unshift({
        text: "",
        value: "",
      });

      return categories;
    },
  },
  watch: {
    value(v) {
      this.value_ = v;
    },
  },
  created() {
    this.$rest("modulesCategories").get({
      params: {
        moduleId: this.moduleId,
      },
      keepCache: true,
    });
  },
  mounted() {
    this.value_ = this.value;
  },
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
