<template lang="pug">
grid-item(col-end="span 2", col-end-sm="span 1")
  .d-flex
    v-select(
      v-model="value_",
      :label="label",
      :items="tagsSelect",
      no-data-text="Nenhuma tag adicionada",
      dense,
      outlined,
      dark,
      multiple,
      chips
    )
    v-btn.ml-1(@click="listDialog = true", icon, small)
      v-icon(small) fas fa-cog
  input(:name="name", :value="valueParsed", type="hidden")
  list(
    v-model="listDialog",
    :field-id="fieldId",
    :tags="tags",
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
    tags() {
      return this.linkModule
        ? this.$rest(this.linkModule).list
        : this.$rest("modulesTags").getters.filterByProperty(
            "fieldId",
            this.fieldId
          );
    },
    tagsSelect() {
      const tags = this.tags.map(({ title, id }) => ({
        text: title,
        value: id,
      }));

      return tags;
    },
    valueParsed() {
      return this.value_ && this.value_.length
        ? JSON.stringify(this.value_)
        : "[]";
    },
  },
  watch: {
    value(v) {
      this.value_ = v ? JSON.parse(v) : [];
    },
  },
  created() {
    if (this.linkModule) {
      this.$rest(this.linkModule).get({ params: { fields: "id,title" } });
    } else {
      this.$rest("modulesTags").get({
        params: {
          moduleId: this.moduleId,
        },
        keepCache: true,
      });
    }
  },
  mounted() {
    this.value_ = this.value ? JSON.parse(this.value) : [];
  },
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
