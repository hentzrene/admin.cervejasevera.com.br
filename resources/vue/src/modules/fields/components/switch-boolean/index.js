import Index from "./Index.vue";

export default {
  name: "switchbooleanfield",
  component: Index,
  formatForDisplay: ({ value }) => {
    if (!value) return "";

    if (value == "0") {
      return "Não";
    } else {
      return "Sim";
    }
  },
};
