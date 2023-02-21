<template>
  <module-template title="Módulos">
    <template #toolbar>
      <toolbar-button
        @click="dialogMenu = true"
        tip="Menu"
        icon="fas fa-bars"
        dark="dark"
      ></toolbar-button>
      <toolbar-button
        @click="$refs.importInput.click()"
        tip="Importar"
        icon="fas fa-file-import"
        dark="dark"
      ></toolbar-button>
      <input
        @input="importFile"
        ref="importInput"
        type="file"
        hidden="hidden"
      />
      <toolbar-button
        :to="$route.path + '/adicionar'"
        tip="Adicionar"
        icon="fas fa-plus"
        dark="dark"
      ></toolbar-button>
    </template>
    <v-data-table
      @click:row="(item) => $router.push($route.path + '/' + item.id)"
      :headers="headers"
      :items="items"
      :loading="loading"
      height="calc(100vh - 114px)"
      sort-by="id"
      loading-text="Carregado dados."
      no-data-text="Não há registros."
      no-results-text="Não há registros."
      sort-desc="sort-desc"
      fixed-header="fixed-header"
      disable-pagination="disable-pagination"
      hide-default-footer="hide-default-footer"
      dark="dark"
    >
      <template #item.action="{ item }">
        <v-btn
          @click.stop="editMenuTitle(item)"
          color="blue"
          icon="icon"
          small="small"
        >
          <v-icon small="small">fas fa-exchange-alt</v-icon>
        </v-btn>
        <v-btn
          class="ml-2"
          @click.stop="remove(item.id)"
          :disabled="item.removable == 0"
          color="red"
          icon="icon"
          small="small"
        >
          <v-icon small="small">fas fa-trash</v-icon>
        </v-btn>
      </template>
    </v-data-table>
    <v-dialog v-model="dialogConfirm" max-width="400" persistent="persistent">
      <v-card dark="dark">
        <v-card-title class="d-flex justify-center"
          >Confirmar remoção</v-card-title
        >
        <v-card-text>
          <p>
            Ao excluir um módulo, será excluído todos os posts e outros e
            registros vinculados à ele.
          </p>
          <p class="mb-0">Tem certeza que deseja continuar?</p>
        </v-card-text>
        <v-card-actions class="d-flex justify-space-between">
          <v-btn
            class="text-none"
            @click="$emit('cancel')"
            color="secondary"
            text="text"
            >Cancelar</v-btn
          >
          <v-btn
            class="text-none"
            @click="$emit('confirm')"
            color="secondary"
            depressed="depressed"
            >Confirmar</v-btn
          >
        </v-card-actions>
      </v-card>
    </v-dialog>
    <menu-config-dialog
      v-model="dialogMenu"
      :menus="menus"
    ></menu-config-dialog>
    <menu-edit-dialog
      v-if="moduleItem"
      v-model="dialogEditMenu"
      :menus="menus"
      :module-item="moduleItem"
    ></menu-edit-dialog>
  </module-template>
</template>

<script>
import ToolbarButton from "@/components/buttons/ToolbarButton";
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
      this.moduleItem = item;
      this.dialogEditMenu = true;
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
