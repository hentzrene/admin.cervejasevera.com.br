import List from "./List";
import Item from "./Item";

export default {
  routes: [
    {
      name: "{name}",
      path: `/admin/{key}`,
      component: List
    },
    {
      name: "{name} / Alterar",
      path: `/admin/{key}/:sub`,
      component: Item
    }
  ]
};
