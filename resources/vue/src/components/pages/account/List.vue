<template lang="pug">
module-template(title="Contas")
  template(#toolbar)
    toolbar-button(
      :to="$route.path + '/adicionar'",
      tip="Adicionar",
      icon="fas fa-plus",
      dark
    )
  v-data-table(
    @click:row="(item) => $router.push($route.path + '/' + item.id)",
    :headers="headers",
    :items="accounts",
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
        :disabled="item.id == userId",
        color="red",
        icon,
        small
      )
        v-icon(small) fas fa-trash
</template>

<script>
import ToolbarButton from "@/components/buttons/Toolbar";
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
