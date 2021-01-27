<template lang="pug">
module-template(title="Módulos")
  template(#toolbar)
    toolbar-button(
      :to="$route.path + '/adicionar'",
      title="Adicionar",
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
      v-btn(
        @click.stop="remove(item.id)",
        :disabled="item.removable == 0",
        color="red",
        icon,
        small
      )
        v-icon(small) fas fa-trash
  v-dialog(v-model="dialog", max-width="400", persistent)
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
</template>

<script>
import ToolbarButton from "@/components/buttons/Toolbar";
import ModuleTemplate from "@/components/templates/Module";

export default {
  data: () => ({
    loading: false,
    headers: [
      { text: "Id", value: "id", align: "left" },
      { text: "Nome", value: "name" },
      { text: "View", value: "viewName" },
      { text: "", value: "action", align: "right" },
    ],
    dialog: null,
  }),
  computed: {
    items() {
      return this.$rest("modules").list;
    },
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
  methods: {
    remove(id) {
      this.confirmation().then(() => this.$rest("modules").delete({ id }));
    },
    confirmation() {
      this.dialog = true;

      return new Promise((resolve, reject) => {
        this.$once("confirm", () => {
          this.dialog = false;
          resolve();
        });
        this.$once("cancel", () => {
          this.dialog = false;
          reject();
        });
      });
    },
  },
  created() {
    this.loading = true;
    this.$rest("modules")
      .get()
      .finally(() => (this.loading = false));
  },
  components: {
    ToolbarButton,
    ModuleTemplate,
  },
};
</script>
