import List from "./List";
import Add from "./Add";
import Edit from "./Edit";

const key = "modules";

export default {
  key,
  routes: [
    {
      name: "Módulos",
      path: `/admin/${key}`,
      component: List
    },
    {
      name: "Adicionar módulo",
      path: `/admin/${key}/adicionar`,
      component: Add
    },
    {
      name: "Alterar módulo",
      path: `/admin/${key}/:module`,
      component: Edit
    }
  ]
};
