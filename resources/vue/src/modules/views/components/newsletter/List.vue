<template>
  <module-template :title="data.name">
    <template #toolbar>
      <toolbar-button
        :to="$route.path + '/broadcast'"
        tip="Transmitir"
        icon="fas fa-paper-plane"
        dark="dark"
      ></toolbar-button>
      <toolbar-button
        @click="remove"
        :disabled="!selecteds.length"
        tip="Remover"
        icon="fas fa-trash"
        dark="dark"
      ></toolbar-button>
      <print-button></print-button>
      <export-button :module-key="data.key" :fields="fields"></export-button>
    </template>
    <div>
      <div class="pa-2 primary lighten-1 module-search">
        <v-text-field
          v-model="search"
          @click:append="get(1)"
          @keydown.enter="get(1)"
          append-icon="fas fa-search"
          label="Pesquisar"
          single-line="single-line"
          hide-details="hide-details"
          dense="dense"
          outlined="outlined"
          dark="dark"
        ></v-text-field>
      </div>
      <v-data-table
        @update:page="changePage"
        v-model="selecteds"
        :headers="headers"
        :items="items"
        :loading="loading"
        :server-items-length="totalItems"
        :items-per-page="itemsPerPage"
        height="calc(100vh - 226px)"
        sort-by="id"
        loading-text="Carregado dados."
        no-data-text="Não há registros."
        no-results-text="Não há registros."
        show-select="show-select"
        sort-desc="sort-desc"
        fixed-header="fixed-header"
        dark="dark"
      >
        <template v-for="(h, i) in listHeaders" v-slot:[`item.${h}`]="{ item }">
          <template v-if="typeof item[h] === 'string'"
            ><span>{{ item[h] }}</span></template
          >
          <template v-else-if="item[h] && typeof item[h] === 'object'">
            <div v-if="item[h].innerHTML" v-html="item[h].innerHTML"></div>
            <component
              v-else-if="item[h].component"
              :is="item[h].component"
              :value="item[h].value"
            ></component>
          </template>
        </template>
        <template #item.action="{ item }">
          <div class="d-flex justify-end">
            <v-btn
              v-if="parseInt(item.active)"
              @click.stop="toggleActive(item.id, false)"
              color="green"
              icon="icon"
              small="small"
            >
              <v-icon small="small">fas fa-eye</v-icon>
            </v-btn>
            <v-btn
              v-else
              @click.stop="toggleActive(item.id, true)"
              color="blue"
              icon="icon"
              small="small"
            >
              <v-icon small="small">fas fa-eye-slash</v-icon>
            </v-btn>
          </div>
        </template>
      </v-data-table>
    </div>
    <v-overlay v-model="loading" absolute="absolute">
      <loading></loading>
    </v-overlay>
  </module-template>
</template>

<script>
import ToolbarButton from "@/components/buttons/Toolbar";
import PrintButton from "@/components/buttons/Print";
import ExportButton from "@/components/buttons/Export";
import ModuleTemplate from "@/components/templates/Module";
import formatForDisplay from "@/modules/fields/formatForDisplay.js";
import Loading from "@/components/tools/Loading";

export default {
  name: "NewsletterList",
  props: {
    data: Object,
  },
  data: () => ({
    loading: true,
    search: "",
    selecteds: [],
    totalItems: 0,
    items: [],
    action: "item.action",
    itemsPerPage: 30,
  }),
  computed: {
    fields() {
      return this.data.fields;
    },
    listHeaders() {
      return this.data.viewOptions.listHeaders;
    },
    headers() {
      if (!this.listHeaders || !this.fields) return [];

      const headers = this.listHeaders.map((h) => {
        const r = {
          value: h,
          align: "left",
          sortable: false,
        };

        switch (h) {
          case "showFrom":
            r.text = "Começo";
            break;
          case "showUp":
            r.text = "Fim";
            break;
          default:
            r.text = this.fields.find(({ key }) => key == h).name;
            break;
        }

        return r;
      });

      headers.unshift({
        text: "Id",
        value: "id",
        align: "left",
        sortable: false,
        filterable: false,
      });
      headers.push({
        text: "",
        value: "action",
        align: "right",
        sortable: false,
        filterable: false,
      });

      return headers;
    },
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
  methods: {
    get(page = 1, itemsPerPage = null) {
      return this.$rest(this.data.key)
        .get({
          params: {
            fields: "id,active," + this.listHeaders.join(","),
            itemsPerPage: itemsPerPage ? itemsPerPage : this.itemsPerPage,
            page,
            search: this.search,
            returnTotalItems: 1,
          },
          save: async (state, { list, totalItems }) => {
            this.totalItems = parseInt(totalItems);
            state.list = list;

            this.items = [];

            const fieldsTypeKeys = Object.fromEntries(
              this.fields.map(({ key, typeKey }) => [key, typeKey])
            );

            for (let item of list) {
              const item_ = {};

              for (let key in item) {
                const fieldTypeKey = fieldsTypeKeys[key];

                if (!fieldsTypeKeys[key]) {
                  if ((key === "showFrom" || key === "showUp") && item[key]) {
                    item_[key] = new Date(item[key]).toLocaleString("pt-BR", {
                      dateStyle: "short",
                      timeStyle: "short",
                    });
                  } else {
                    item_[key] = item[key];
                  }
                } else {
                  if (!formatForDisplay[fieldTypeKey]) {
                    item_[key] = item[key];
                  } else {
                    const v = formatForDisplay[fieldTypeKey]({
                      component: this,
                      value: item[key],
                      fieldData: this.fields.find((field) => field.key === key),
                      moduleId: this.data.id,
                    });

                    if (v instanceof Promise) item_[key] = await v;
                    else item_[key] = v;
                  }
                }
              }

              this.items.push(item_);
            }

            this.loading = false;
          },
        })
        .catch(() => this.$router.replace("/"));
    },
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest(this.data.key)
            .delete({ id })
            .then(() => {
              this.items.splice(
                this.items.findIndex((v) => v.id == id),
                1
              );

              this.loading = false;
            })
        )
      ).finally(() => (this.loading = false));
    },
    toggleActive(id, active) {
      this.$rest(this.data.key)
        .put({
          id,
          prop: "active",
          data: { value: active | 0 },
        })
        .then(
          () => (this.items.find((item) => item.id == id).active = active | 0)
        );
    },
    changePage(page) {
      this.loading = true;

      this.get(page).finally(() => {
        this.loading = false;
      });
    },
    broadcast() {
      console.log("ok");
    },
  },
  created() {
    this.loading = true;
    this.get();
  },
  components: {
    ToolbarButton,
    PrintButton,
    ExportButton,
    ModuleTemplate,
    Loading,
  },
};
</script>
