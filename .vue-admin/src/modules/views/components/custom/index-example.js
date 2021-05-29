// example.js
import List from "./List";
import Add from "./Add";
import Item from "./Item";

const key = "example";

export default {
  key,
  routes: [
    {
      name: "Exemplos",
      path: `/${key}`,
      component: List
    },
    {
      name: "Adicionar exemplo",
      path: `/${key}/adicionar`,
      component: Add
    },
    {
      name: "Alterar exemplo",
      path: `/${key}/:example`,
      component: Item
    }
  ]
};

/******************************************/

// index.js
import example from "./example";

export default [example];
