import List from "./List";
import Broadcast from "./Broadcast";
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
      name: "{name} / Transmissão",
      path: `/{key}/broadcast`,
      component: Broadcast,
    },
  ],
};
