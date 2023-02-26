<template>
  <ModuleTemplate :title="data.name">
    <template #toolbar>
      <RemoveItemButton
        @loading="(val) => (loading = val)"
        :module-key="moduleKey"
        :disabled="!selecteds.length"
        :selecteds="selecteds"
        :items="items"
      />
      <AddItemButton
        @loading="(val) => (loading = val)"
        :module-key="moduleKey"
      />
      <PrintButton />
      <ExportButton :module-key="data.key" :fields="fields" />
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
        @click:row="showItem"
        @update:page="changePage"
        v-model="selecteds"
        :headers="headers"
        :items="items"
        :loading="loading"
        :server-items-length="totalItems"
        :items-per-page="itemsPerPage"
        height="calc(100vh - 226px)"
        sort-by="id"
        loading-text="Carregando dados."
        no-data-text="Não há registros."
        no-results-text="Não há registros."
        show-select
        sort-desc
        fixed-header
        dark
      >
        <template
          v-for="header in headersWithFilter"
          v-slot:[`header.${header.value}`]
        >
          <component
            :is="header.fieldFilterComponent"
            :key="header.value"
            :text="header.text"
            :field="header.field"
            :options="filtersOptions[header.value],"
            @filter="filter"
          />
        </template>

        <template
          v-for="{ key } in columnsWithDisplay"
          v-slot:[`item.${key}`]="{ item }"
        >
          <span v-if="typeof item[key] === 'string'" :key="key">{{
            item[key]
          }}</span>

          <template v-else-if="item[key] && typeof item[key] === 'object'">
            <div
              v-if="item[key].innerHTML"
              v-html="item[key].innerHTML"
              :key="key"
            ></div>
            <component
              v-else-if="item[key].component"
              :is="item[key].component"
              :item="item"
              :name="key"
              :value="item[key].value"
              :key="key"
            />
          </template>
        </template>

        <template #item.action="{ item }">
          <ToggleActiveButton
            :module-key="moduleKey"
            :item="item"
            :items="items"
          />
        </template>
      </v-data-table>
    </div>

    <v-overlay v-model="loading" absolute="absolute">
      <Loading></Loading>
    </v-overlay>
  </ModuleTemplate>
</template>

<script>
import AddItemButton from "./AddItemButton.vue";
import RemoveItemButton from "./RemoveItemButton.vue";
import ToggleActiveButton from "./ToggleActiveButton.vue";
import PrintButton from "../../../../components/buttons/PrintButton.vue";
import ExportButton from "../../../../components/buttons/ExportButton.vue";
import ModuleTemplate from "../../../../components/templates/Module.vue";
import { fieldsFormatForDisplay } from "../../../../modules/fields";
import { fieldsHeaderFilterComponents } from "../../../../modules/fields";
import Loading from "../../../../components/tools/Loading.vue";

export default {
  name: "TableList",
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
    filtersOptions: {},
    filtersQuery: {},
  }),
  computed: {
    fields() {
      return this.data.fields;
    },
    fieldsMap() {
      return Object.fromEntries(this.fields.map((field) => [field.key, field]));
    },
    listHeaders() {
      return this.data.viewOptions.listHeaders;
    },
    headers() {
      if (!this.fields || !this.listHeaders) return [];

      const headers = this.listHeaders.map(this.createHeaderFromField);

      return [
        ...headers,
        this.createHeader({
          text: "",
          value: "action",
          align: "right",
        }),
      ];
    },
    headersWithFilter() {
      return this.headers.filter((header) => !!header.fieldFilterComponent);
    },
    columnsWithDisplay() {
      return this.fields.filter(
        (filter) => filter.typeKey in fieldsFormatForDisplay
      );
    },
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
    moduleKey() {
      return this.data.key;
    },
  },
  methods: {
    get(page = 1, itemsPerPage = null) {
      const strListHeaders = this.listHeaders.join(",");

      const params = {
        fields: "id,active" + (strListHeaders ? `,${strListHeaders}` : ""),
        itemsPerPage: itemsPerPage ? itemsPerPage : this.itemsPerPage,
        page,
        search: this.search,
        returnTotalItems: 1,
        ...this.filtersQuery,
      };

      return this.$rest(this.moduleKey)
        .get({
          params,
          save: async (state, { list, totalItems }) => {
            this.totalItems = parseInt(totalItems);
            state.list = list;

            await this.parseItems(list);

            this.loading = false;
          },
        })
        .catch(() => this.$router.replace("/"));
    },
    async parseItems(list) {
      this.items = [];

      for (let item of list) {
        const item_ = await this.parseItem(item);

        this.items.push(item_);
      }
    },
    async parseItem(item) {
      const item_ = {};

      for (let key in item) {
        const field = this.fieldsMap[key];

        if (!field && ["showFrom", "showUp"].includes(key)) {
          item_[key] = fieldsFormatForDisplay["datetime"]({
            value: item[key],
          });
        } else if (field && fieldsFormatForDisplay[field.typeKey]) {
          item_[key] = await fieldsFormatForDisplay[field.typeKey]({
            id: item.id,
            item,
            component: this,
            value: item[key],
            fieldData: this.fields.find((field) => field.key === key),
            moduleId: this.data.id,
          });
        } else {
          item_[key] = item[key];
        }
      }

      return item_;
    },
    showItem(item) {
      const route = this.$route.path + "/" + item.id;

      this.$router.push(route);
    },
    changePage(page) {
      this.loading = true;

      this.get(page);
    },
    filter() {
      const query = new URLSearchParams();

      function filtersAppendField(query, fieldKey) {
        const filters = query.get("filters")
          ? query.get("filters").split(",")
          : [];

        if (!filters.includes(fieldKey)) filters.push(fieldKey);

        query.set("filters", filters.join(","));
      }

      for (let fieldKey in this.filtersOptions) {
        const filterOptions = this.filtersOptions[fieldKey];

        if (!filterOptions.active) continue;

        filtersAppendField(query, fieldKey);

        for (let [key, value] of filterOptions.query) {
          query.set(key, value);
        }
      }

      this.filtersQuery = Object.fromEntries(query.entries());

      this.loading = true;
      this.get();
    },
    findFieldByKey(key) {
      return this.fields.find((field) => field.key == key);
    },
    createHeader(options) {
      const defaultOptions = {
        align: "left",
        sortable: false,
        filterable: false,
      };

      const mergedOptions = Object.assign(defaultOptions, options);

      return mergedOptions;
    },
    createHeaderFromField(fieldKey) {
      const systemFields = {
        id: "Id",
        showFrom: "Começo",
        showUp: "Fim",
      };

      if (fieldKey in systemFields) {
        return this.createHeader({
          value: fieldKey,
          text: systemFields[fieldKey],
        });
      }

      const field = this.fieldsMap[fieldKey];

      const fieldFilterComponent = fieldsHeaderFilterComponents[field.typeKey];

      if (fieldFilterComponent) {
        this.filtersOptions[fieldKey] = {
          active: false,
        };
      }

      return this.createHeader({
        value: fieldKey,
        text: field.name,
        field: field,
        fieldFilterComponent,
      });
    },
  },
  created() {
    this.loading = true;
    this.get();
  },
  components: {
    AddItemButton,
    RemoveItemButton,
    ToggleActiveButton,
    PrintButton,
    ExportButton,
    ModuleTemplate,
    Loading,
  },
};
</script>
