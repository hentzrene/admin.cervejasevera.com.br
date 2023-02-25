<template>
  <div>
    <v-menu left="left" offset-y="offset-y" :close-on-content-click="false">
      <template v-slot:activator="{ on, attrs }">
        <div class="switchboolean-table-filter-label" v-on="on">
          <span>{{ text }}</span>
          <button
            class="switchboolean-table-filter-btn ml-2"
            v-bind="attrs"
            type="button"
          >
            <v-icon size="11">fas fa-chevron-down</v-icon>
          </button>
        </div>
      </template>
      <div class="switchboolean-table-filter-wrap">
        <v-autocomplete
          v-model="selectedItem"
          :items="items"
          :label="text"
          background-color="primary"
          hide-details
          dark
          solo
          autofocus
        ></v-autocomplete>
      </div>
    </v-menu>
  </div>
</template>

<script>
export default {
  props: {
    text: {
      type: String,
      required: true,
    },
    field: {
      type: Object,
      required: true,
    },
    options: {
      type: Object,
      default: () => ({
        active: false,
        query: null,
      }),
    },
  },
  data: () => ({
    selectedItem: null,
    items: [
      {
        text: "",
        value: null,
      },
      {
        text: "Sim",
        value: 1,
      },
      {
        text: "Não",
        value: 0,
      },
    ],
  }),
  methods: {
    filter() {
      if (this.selectedItem !== null) {
        const query = new URLSearchParams();
        query.set(`filter_${this.field.key}_value`, this.selectedItem);

        this.options.query = query;
        this.options.active = true;

        this.$emit("filter");
      } else {
        this.options.active = false;
        this.options.query = null;
        this.$emit("filter");
      }
    },
  },
  watch: {
    selectedItem() {
      this.filter();
    },
  },
};
</script>

<style>
.switchboolean-table-filter-label {
  cursor: pointer;
  width: max-content;
}

.switchboolean-table-filter-btn {
  background: var(--v-primary-lighten3);
  border-radius: 2px;
  height: 18px;
  width: 18px;
  transition: 0.3s;
}

.switchboolean-table-filter-btn:hover {
  opacity: 0.8;
}

.switchboolean-table-filter-wrap {
  max-width: 200px;
}
</style>
