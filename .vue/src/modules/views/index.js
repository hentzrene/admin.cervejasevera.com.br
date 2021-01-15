import custom from "./custom";
import table from "./table";
import item from "./item";

const views = { custom, table, item };
const components = Object.fromEntries(
  custom
    .flatMap(({ routes }) => routes)
    .concat(table.routes)
    .map(({ component }) => [component.name, component])
);

export { views, components };
