import Index from "./Index.vue";

export default {
  routes: [
    {
      name: "{name} / Alterar",
      path: `/admin/{key}`,
      component: Index
    }
  ]
};
