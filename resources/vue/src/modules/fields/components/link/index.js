import Display from "./Display.vue";
import Index from "./Index.vue";

export default {
  name: "linkfield",
  component: Index,
  formatForDisplay: ({ value }) => {
    return {
      value,
      component: Display,
    };
  },
};
