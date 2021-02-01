import List from "./List";
import Add from "./Add";
import Item from "./Item";

const key = "accounts";

export default {
  key,
  routes: [
    {
      name: "Contas",
      path: `/admin/${key}`,
      component: List
    },
    {
      name: "Adicionar conta",
      path: `/admin/${key}/adicionar`,
      component: Add
    },
    {
      name: "Alterar conta",
      path: `/admin/${key}/:account`,
      component: Item
    }
  ]
};
