<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  .d-flex
    v-select(
      v-model="value_",
      :label="label",
      :name="name",
      :items="categoriesSelect",
      no-data-text="Nenhuma categoria adicionada",
      dense,
      outlined,
      dark
    )
    v-btn.ml-1(@click="listDialog = true", icon, small)
      v-icon(small) fas fa-cog
  list(
    v-model="listDialog",
    :field-id="fieldId",
    :categories="categories",
    :link-module="linkModule",
    :is-admin-user="isAdminUser"
  )
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
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub;
    },
    linkModule() {
      return this.fieldOptions.linkModule;
    },
    categories() {
      return this.linkModule
        ? this.$rest(this.linkModule).list
        : this.$rest("modulesCategories").getters.filterByProperty(
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
    if (this.linkModule) {
      this.$rest(this.linkModule).get({ params: { fields: "id,title" } });
    } else {
      this.$rest("modulesCategories").get({
        params: {
          moduleId: this.moduleId,
        },
        keepCache: true,
      });
    }
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
