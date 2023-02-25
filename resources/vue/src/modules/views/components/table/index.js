import List from "./List";
import Item from "./Item";
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
      name: "{name}",
      path: `/{key}`,
      component: List,
    },
    {
      name: "{name} / Alterar",
      path: `/{key}/:sub`,
      component: Item,
    },
  ],
};
