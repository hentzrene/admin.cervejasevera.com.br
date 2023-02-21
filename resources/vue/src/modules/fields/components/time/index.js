import Index from "./Index.vue";

export default {
  name: "timefield",
  component: Index,
  formatForDisplay: ({ value }) => value && value.slice(0, 5),
};
