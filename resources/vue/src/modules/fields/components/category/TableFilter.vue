<template lang="pug">
div
  v-menu(left, offset-y, :close-on-content-click="false")
    template(v-slot:activator="{ on, attrs }")
      .category-table-filter-label(v-on="on")
        span {{ text }}
        button.category-table-filter-btn.ml-2(v-bind="attrs", type="button")
          v-icon(size="11") fas fa-chevron-down
    .category-table-filter-checkboxes-wrap.primary.lighten-2.py-2.px-4
      v-checkbox.mt-0(
        v-for="({ text, value }, i) in categories",
        v-model="selectedsCategories",
        :key="i",
        :label="text",
        :value="value",
        hide-details,
        dark
      )
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
    selectedsCategories: [],
  }),
  computed: {
    linkModule() {
      return this.field.options.linkModule;
    },
    categories() {
      const categories = this.linkModule
        ? this.$rest(this.linkModule).list
        : this.$rest("modulesCategories").getters.filterByProperty(
            "fieldId",
            this.field.id
          );

      return categories.map(({ title, id }) => ({
        text: title,
        value: id,
      }));
    },
  },
  methods: {
    filter() {
      if (this.selectedsCategories.length) {
        const query = new URLSearchParams();
        query.set(
          `filter_${this.field.key}_categories`,
          this.selectedsCategories.join(",")
        );

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
    selectedsCategories() {
      this.filter();
    },
  },
  created() {
    if (this.linkModule) {
      this.$rest(this.linkModule).get({ params: { fields: "id,title" } });
    } else {
      this.$rest("modulesCategories").get({
        params: {
          moduleId: this.moduleId,
        },
        keepCache: true,
      });
    }
  },
};
</script>

<style>
.category-table-filter-label {
  cursor: pointer;
  width: max-content;
}

.category-table-filter-btn {
  background: var(--v-primary-lighten3);
  border-radius: 2px;
  height: 18px;
  width: 18px;
  transition: 0.3s;
}

.category-table-filter-btn:hover {
  opacity: 0.8;
}

.category-table-filter-checkboxes-wrap {
  max-width: 200px;
  min-width: 160px;
  display: grid;
  gap: 16px;
}

.category-table-filter-checkboxes-wrap label {
  font-size: 13px;
  font-weight: 500;
}
.category-table-filter-checkboxes-wrap i::before {
  font-size: 18px !important;
}
</style>
