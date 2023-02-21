import Index from "./Index.vue";

export default {
  name: "datefield",
  component: Index,
  formatForDisplay: ({ value }) =>
    value &&
    new Date(value + "T12:00").toLocaleString("pt-BR", {
      dateStyle: "short",
    }),
};
