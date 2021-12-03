import List from "./List";
import Broadcast from "./Broadcast";

export default {
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
