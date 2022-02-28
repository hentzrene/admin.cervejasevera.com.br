<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="500px"
)
  v-card.pa-4(dark)
    .title.mb-4.d-flex.flex-column.flex-sm-row.justify-space-between
      div Menu
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
      @click:row="(menu) => openEditDialog(menu)",
      :headers="headers",
      :items="menus",
      :mobile-breakpoint="0",
      v-if="menus.length",
      v-model="selecteds",
      no-data-text="Não há registros.",
      sort-by="id",
      sort-desc,
      show-select,
      disable-pagination,
      hide-default-footer
    )
    .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum menu foi adicionada.
  v-overlay(v-if="value", v-model="loading")
    loading
  add(v-model="addDialog")
  edit(
    v-if="menuSelectedToEdit",
    v-model="editDialog",
    :menu="menuSelectedToEdit"
  )
</template>

<script>
import Add from "./Add";
import Edit from "./Edit";
import Loading from "@/components/tools/Loading";
import { required } from "@/components/forms/rules.js";

export default {
  props: {
    value: Boolean,
    menus: Array,
  },
  data: () => ({
    addDialog: false,
    editDialog: false,
    loading: false,
    headers: [
      {
        text: "Título",
        value: "title",
        align: "left",
      },
    ],
    editMenuTitle: null,
    rules: {
      required,
    },
    selecteds: [],
    menuSelectedToEdit: null,
  }),
  methods: {
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest("modulesMenu").delete({
            id: id,
          })
        )
      ).finally(() => {
        this.selecteds = [];
        this.loading = false;
      });
    },
    editTitle(menuId) {
      const currentMenuTitle = this.menus.find(({ id }) => id == menuId).title;
      if (!this.editMenuTitle || currentMenuTitle === this.editMenuTitle)
        return;

      this.loading = true;

      this.$rest("modulesMenu")
        .put({
          id: menuId,
          prop: "title",
          data: {
            value: this.editMenuTitle,
          },
        })
        .then(() => (this.loading = false));
    },
    openEditDialog(menu) {
      this.menuSelectedToEdit = menu;
      this.editDialog = true;
    },
  },
  components: {
    Loading,
    Add,
    Edit,
  },
};
</script>
