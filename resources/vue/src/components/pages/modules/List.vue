<template lang="pug">
module-template(title="Módulos")
  template(#toolbar)
    toolbar-button(
      @click="dialogMenu = true",
      tip="Menu",
      icon="fas fa-bars",
      dark
    )
    toolbar-button(
      @click="$refs.importInput.click()",
      tip="Importar",
      icon="fas fa-file-import",
      dark
    )
    input(@input="importFile", ref="importInput", type="file", hidden)
    toolbar-button(
      :to="$route.path + '/adicionar'",
      tip="Adicionar",
      icon="fas fa-plus",
      dark
    )
  v-data-table(
    @click:row="(item) => $router.push($route.path + '/' + item.id)",
    :headers="headers",
    :items="items",
    :loading="loading",
    height="calc(100vh - 114px)",
    sort-by="id",
    loading-text="Carregado dados.",
    no-data-text="Não há registros.",
    no-results-text="Não há registros.",
    sort-desc,
    fixed-header,
    disable-pagination,
    hide-default-footer,
    dark
  )
    template(#item.action="{ item }")
      v-btn(@click.stop="editMenuTitle(item)", color="blue", icon, small)
        v-icon(small) fas fa-exchange-alt
      v-btn.ml-2(
        @click.stop="remove(item.id)",
        :disabled="item.removable == 0",
        color="red",
        icon,
        small
      )
        v-icon(small) fas fa-trash
  v-dialog(v-model="dialogConfirm", max-width="400", persistent)
    v-card(dark)
      v-card-title.d-flex.justify-center Confirmar remoção
      v-card-text
        p Ao excluir um módulo, será excluído todos os posts e outros e registros vinculados à ele.
        p.mb-0 Tem certeza que deseja continuar?
      v-card-actions.d-flex.justify-space-between
        v-btn.text-none(@click="$emit('cancel')", color="secondary", text) Cancelar
        v-btn.text-none(
          @click="$emit('confirm')",
          color="secondary",
          depressed
        ) Confirmar
  menu-config-dialog(v-model="dialogMenu", :menus="menus")
  menu-edit-dialog(
    v-model="dialogEditMenu",
    :menus="menus",
    :module-item="moduleItem"
  )
</template>

<script>
import ToolbarButton from "@/components/buttons/Toolbar";
import ModuleTemplate from "@/components/templates/Module";
import MenuConfigDialog from "./menu-config-dialog/Index";
import MenuEditDialog from "./MenuEditDialog";

export default {
  data: () => ({
    loading: false,
    headers: [
      { text: "Id", value: "id", align: "left" },
      { text: "Nome", value: "name" },
      { text: "View", value: "viewName" },
      { text: "Menu", value: "menuTitle" },
      { text: "Submenu", value: "submenuTitle" },
      { text: "", value: "action", align: "right" },
    ],
    dialogConfirm: false,
    dialogMenu: false,
    dialogEditMenu: false,
    moduleItem: null,
  }),
  computed: {
    items() {
      return this.$rest("modules").list;
    },
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
    menus() {
      return this.$rest("modulesMenu").list;
    },
  },
  methods: {
    remove(id) {
      this.confirmation().then(() => this.$rest("modules").delete({ id }));
    },
    confirmation() {
      this.dialogConfirm = true;

      return new Promise((resolve, reject) => {
        this.$once("confirm", () => {
          this.dialogConfirm = false;
          resolve();
        });
        this.$once("cancel", () => {
          this.dialogConfirm = false;
          reject();
        });
      });
    },
    editMenuTitle(item) {
      this.dialogEditMenu = true;
      this.moduleItem = item;
    },
    importFile({ target }) {
      if (!target.files.length) return;

      this.loading = true;

      const reader = new FileReader();
      reader.onload = (e) => {
        this.$rest("modules")
          .post({
            data: JSON.parse(e.target.result),
          })
          .finally(() => {
            target.value = "";
            this.loading = false;
          });
      };

      reader.readAsText(target.files[0]);
    },
  },
  created() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }

    this.loading = true;
    this.$rest("modulesMenu").get();
    this.$rest("modules")
      .get()
      .finally(() => (this.loading = false));
  },
  components: {
    ToolbarButton,
    ModuleTemplate,
    MenuConfigDialog,
    MenuEditDialog,
  },
};
</script>