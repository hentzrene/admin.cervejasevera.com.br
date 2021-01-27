<template lang="pug">
module-template(:title="data.name")
  template(#toolbar)
    toolbar-button(
      @click="remove",
      :disabled="!selecteds.length",
      title="Remover",
      icon="fas fa-trash",
      dark
    )
    toolbar-button(@click="add", title="Adicionar", icon="fas fa-plus", dark)
    print-button
  div
    .pa-2.primary.lighten-1
      v-text-field(
        v-model="search",
        @click:append="get(1)",
        @keydown.enter="get(1)",
        append-icon="fas fa-search",
        label="Pesquisar",
        single-line,
        hide-details,
        dense,
        outlined,
        dark
      )
    v-data-table(
      @click:row="(item) => $router.push($route.path + '/' + item.id)",
      @update:page="changePage",
      v-model="selecteds",
      :headers="headers",
      :items="items",
      :loading="loading",
      :server-items-length="totalItems",
      :items-per-page="100",
      height="calc(100vh - 226px)",
      sort-by="id",
      loading-text="Carregado dados.",
      no-data-text="Não há registros.",
      no-results-text="Não há registros.",
      show-select,
      sort-desc,
      fixed-header,
      dark
    )
      template(#item.action="{ item }")
        .d-flex.justify-end
          v-btn(
            v-if="parseInt(item.active)",
            @click.stop="toggleActive(item.id, false)",
            color="green",
            icon,
            small
          )
            v-icon(small) fas fa-eye
          v-btn(
            v-else,
            @click.stop="toggleActive(item.id, true)",
            color="blue",
            icon,
            small
          )
            v-icon(small) fas fa-eye-slash
      template(#item.date="{ item }")
        span.text-no-wrap {{ item.date | formatDate }}
  v-overlay(v-model="loading", absolute)
    v-progress-circular(:size="50", color="secondary", indeterminate, absolute)
</template>

<script>
import ToolbarButton from "@/components/buttons/Toolbar";
import PrintButton from "@/components/buttons/Print";
import ModuleTemplate from "@/components/templates/Module";

export default {
  name: "TableList",
  props: {
    data: Object,
  },
  data: () => ({
    loading: false,
    search: "",
    selecteds: [],
    totalItems: 0,
  }),
  computed: {
    viewOptions() {
      return this.data.viewOptions;
    },
    fields() {
      return this.data.fields;
    },
    headers() {
      if (!this.viewOptions.listHeaders || !this.fields) return [];

      const viewOptions = this.viewOptions.listHeaders.map((h) => {
        return {
          text: this.fields.find(({ key }) => key == h).name,
          value: h,
          align: "left",
          sortable: false,
        };
      });

      viewOptions.unshift({
        text: "Id",
        value: "id",
        align: "left",
        sortable: false,
        filterable: false,
      });
      viewOptions.push({
        text: "",
        value: "action",
        align: "right",
        sortable: false,
        filterable: false,
      });

      return viewOptions;
    },
    items() {
      return this.$rest(this.data.key).list;
    },
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
  methods: {
    get(page = 1, itemsPerPage = 100) {
      return this.$rest(this.data.key)
        .get({
          params: {
            fields: "id,active," + this.data.viewOptions.listHeaders.join(","),
            itemsPerPage,
            page,
            search: this.search,
            returnTotalItems: 1,
          },
          save: (state, { list, totalItems }) => {
            this.totalItems = parseInt(totalItems);
            state.list = list;
          },
        })
        .catch(() => this.$router.replace("/admin"))
        .finally(() => (this.loading = false));
    },
    add() {
      this.$rest(this.data.key)
        .post()
        .then(({ id }) => this.$router.push(this.$route.path + "/" + id));
    },
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest(this.data.key)
            .delete({ id })
            .then(() => (this.loading = false))
        )
      ).finally(() => (this.loading = false));
    },
    toggleActive(id, active) {
      this.$rest(this.data.key).put({
        id,
        prop: "active",
        data: { value: active | 0 },
      });
    },
    changePage(page) {
      this.loading = true;

      this.get(page).finally(() => {
        this.loading = false;
      });
    },
  },
  filters: {
    formatDate: (v) => {
      return new Date(v).toLocaleString("pt-BR", {
        dateStyle: "short",
        timeStyle: "short",
      });
    },
  },
  created() {
    this.loading = true;
    this.get();
  },
  components: {
    ToolbarButton,
    PrintButton,
    ModuleTemplate,
  },
};
</script>
