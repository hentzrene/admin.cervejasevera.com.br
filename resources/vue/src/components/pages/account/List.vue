<template>
  <module-template title="Contas">
    <template #toolbar>
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
      :items="accounts"
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
          @click.stop="remove(item.id)"
          :disabled="item.id == userId"
          color="red"
          icon="icon"
          small="small"
        >
          <v-icon small="small">fas fa-trash</v-icon>
        </v-btn>
      </template>
    </v-data-table>
  </module-template>
</template>

<script>
import ToolbarButton from "@/components/buttons/ToolbarButton";
import ModuleTemplate from "@/components/templates/Module";

export default {
  name: "AccountList",
  data: () => ({
    loading: false,
    headers: [
      { text: "Id", value: "id", align: "left" },
      { text: "Nome", value: "name" },
      { text: "E-mail", value: "email" },
      { text: "", value: "action", align: "right" },
    ],
  }),
  computed: {
    accounts() {
      return this.$rest("accounts").list;
    },
    userId() {
      return this.$store.state.user.id;
    },
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
  methods: {
    remove(id) {
      this.$rest("accounts").delete({ id });
    },
  },
  created() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }

    this.loading = true;
    this.$rest("accounts")
      .get()
      .finally(() => (this.loading = false));
  },
  components: {
    ToolbarButton,
    ModuleTemplate,
  },
};
</script>
