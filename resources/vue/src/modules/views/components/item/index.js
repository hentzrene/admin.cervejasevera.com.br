import Index from "./Index.vue";
import AddModule from "./_module/add/Index.vue";
import EditModule from "./_module/edit/Index.vue";

export default {
  moduleConfig: {
    components: {
      add: AddModule,
      edit: EditModule,
    },
  },
  routes: [
    {
      name: "{name} / Alterar",
      path: `/{key}`,
      component: Index,
    },
  ],
};
