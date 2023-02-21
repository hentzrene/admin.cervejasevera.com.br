import Index from "./Index.vue";

export default {
  name: "pricefield",
  component: Index,
  formatForDisplay: ({ value }) =>
    value &&
    new Intl.NumberFormat("pt-BR", {
      style: "currency",
      currency: "BRL",
    }).format(value),
};
