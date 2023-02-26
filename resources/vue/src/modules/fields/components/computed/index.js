import Display from "./Display.vue";
import Index from "./Index.vue";

export default {
  name: "computedfield",
  component: Index,
  formatForDisplay: ({ value }) => {
    return {
      value,
      component: Display,
    };
  },
};
