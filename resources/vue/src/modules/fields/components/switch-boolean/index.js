import Index from "./Index.vue";
import TableFilter from "./TableFilter.vue";
import Display from "./Display.vue";

export default {
  name: "switchbooleanfield",
  component: Index,
  headerFilterComponent: TableFilter,
  formatForDisplay: ({ value }) => {
    return {
      value,
      component: Display,
    };
  },
};
