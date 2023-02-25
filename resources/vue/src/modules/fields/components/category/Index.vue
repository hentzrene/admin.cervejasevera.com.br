<template>
  <grid-item col-end="span 2" col-end-sm="span 1">
    <div class="d-flex">
      <v-autocomplete
        v-model="value_"
        :label="label"
        :name="name"
        :items="categoriesSelect"
        no-data-text="Nenhuma categoria adicionada"
        dense
        outlined
        dark
      />
      <v-btn class="ml-1" @click="listDialog = true" icon="icon" small="small">
        <v-icon small="small">fas fa-cog</v-icon>
      </v-btn>
    </div>
    <ListDialog
      v-model="listDialog"
      :field-id="fieldId"
      :categories="categories"
      :link-module="linkModule"
      :is-admin-user="isAdminUser"
    />
  </grid-item>
</template>

<script>
import ListDialog from "./ListDialog";
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
    item: {
      type: Object,
      required: true,
    },
    itemId: {
      type: [String, Number],
      required: true,
    },
    moduleId: {
      type: [String, Number],
      required: true,
    },
  },
  data: () => ({
    listDialog: false,
    value_: null,
  }),
  computed: {
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
  components: {
    ListDialog,
  },
};
</script>
