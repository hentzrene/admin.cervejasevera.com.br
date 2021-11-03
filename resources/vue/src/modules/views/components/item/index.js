import Index from "./Index.vue";

export default {
  routes: [
    {
      name: "{name} / Alterar",
      path: `/{key}`,
      component: Index,
    },
  ],
};
