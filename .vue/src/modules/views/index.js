import custom from "./custom";
import table from "./table";

const views = { custom, table };
const components = Object.fromEntries(
  custom
    .flatMap(({ routes }) => routes)
    .concat(table.routes)
    .map(({ component }) => [component.name, component])
);

export { views, components };
