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
  list(
    @add="(data) => (this.categories = data)",
    v-model="listDialog",
    :field-id="fieldId",
    :categories="categories"
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
  },
  data: () => ({
    listDialog: false,
    categories: [],
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub;
    },
    categoriesSelect() {
      return this.categories.map(({ title, id }) => ({
        text: title,
        value: id,
      }));
    },
  },
  methods: {
    get() {
      return this.$rest("modulesCategories")
        .get({
          id: this.itemId,
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
          },
        })
        .then((categories) => (this.categories = categories));
    },
  },
  created() {
    this.get();
  },
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
