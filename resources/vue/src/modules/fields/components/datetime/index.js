import Index from "./Index.vue";

export default {
  name: "datetimefield",
  component: Index,
  formatForDisplay: ({ value }) =>
    value &&
    new Date(value).toLocaleString("pt-BR", {
      dateStyle: "short",
      timeStyle: "short",
    }),
};
