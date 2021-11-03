import List from "./List";
import Item from "./Item";

export default {
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
