<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="500px"
)
  v-card.pa-4(dark)
    .title.mb-4.d-flex.flex-column.flex-sm-row.justify-space-between
      div Tags
      div
        v-btn.mr-2.text-none(
          @click="remove",
          color="secondary",
          :disabled="!selecteds.length",
          depressed,
          small
        )
          v-icon(left, small) fas fa-trash
          span Remover
        v-btn.text-none(
          @click="addDialog = true",
          color="secondary",
          depressed,
          small
        )
          v-icon(left, small) fas fa-plus
          span Adicionar
    v-data-table(
      :headers="headers",
      :items="tags",
      :mobile-breakpoint="0",
      v-if="tags.length",
      v-model="selecteds",
      no-data-text="Não há registros.",
      sort-by="id",
      sort-desc,
      show-select,
      disable-pagination,
      hide-default-footer
    )
      template(#item.title="{ item }")
        v-edit-dialog.primary.lighten-4(
          @save="editTitle(item.id)",
          :return-value.sync="editTagTitle",
          dark
        ) {{ item.title }}
          template(#input)
            v-text-field(
              :value="item.title",
              @input="(data) => (editTagTitle = data)",
              :rules="[rules.required]",
              label="Renomear",
              single-line,
              counter
            )
    .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-else) Nenhuma categoria foi adicionada.
  v-overlay(v-if="value", v-model="loading")
    loading
  add(v-model="addDialog", :field-id="fieldId")
</template>

<script>
import Add from "./Add";
import Loading from "@/components/tools/Loading";
import { required } from "@/components/forms/rules.js";

export default {
  props: {
    value: Boolean,
    fieldId: {
      type: Number,
      required: true,
    },
    tags: {
      type: Array,
      default: () => [],
    },
  },
  data: () => ({
    addDialog: false,
    loading: false,
    headers: [
      {
        text: "Título",
        value: "title",
        align: "left",
      },
    ],
    editTagTitle: null,
    rules: {
      required,
    },
    selecteds: [],
  }),
  computed: {
    moduleKey() {
      return this.$rest("modules").item.key;
    },
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub || 1;
    },
  },
  methods: {
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest("modulesTags").delete({
            id: id,
            params: { moduleId: this.moduleId },
          })
        )
      ).finally(() => {
        this.selecteds = [];
        this.loading = false;
      });
    },
    editTitle(tagId) {
      const currentTagTitle = this.tags.find(({ id }) => id == tagId).title;
      if (!this.editTagTitle || currentTagTitle === this.editTagTitle) return;

      this.loading = true;

      this.$rest("modulesTags")
        .put({
          id: tagId,
          prop: "title",
          data: {
            value: this.editTagTitle,
          },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() => (this.loading = false));
    },
  },
  components: {
    Loading,
    Add,
  },
};
</script>
