<template lang="pug">
template-dialog-any(
  @input="(data) => $emit('input', data)",
  :value="value",
  title="Tags",
  max-width="500px"
)
  template(#actions)
    template-dialog-header-button(
      @click="remove",
      :disabled="!selecteds.length",
      icon="fas fa-trash",
      text="Remover"
    )
    template-dialog-header-button(
      @click="addDialog = true",
      icon="fas fa-plus",
      text="Adicionar"
    )
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
import TemplateDialogAny from "../../templates/DialogAny";
import TemplateDialogHeaderButton from "../../templates/DialogHeaderButton";
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
    TemplateDialogAny,
    TemplateDialogHeaderButton,
    Loading,
    Add,
  },
};
</script>
