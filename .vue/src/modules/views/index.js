import custom from "./components/custom";
import table from "./components/table";
import item from "./components/item";

const views = { custom, table, item },
  viewAddModuleComponents = {},
  viewEditModuleComponents = {};

require
  .context("./components", true, /^\.\/[^/]+$/)
  .keys()
  .map(path => {
    const view = path.match(/^\.\/[^/]+$/)[0].slice(2);

    if (
      !(view in viewAddModuleComponents) &&
      !(view in viewEditModuleComponents)
    ) {
      viewAddModuleComponents[view] = [];
      viewEditModuleComponents[view] = [];
    }

    viewAddModuleComponents[view] = import(
      `./components/${view}/_module/add/Index.vue`
    );
    viewEditModuleComponents[view] = import(
      `./components/${view}/_module/edit/Index.vue`
    );
  });

let components = [];

for (let view in views) {
  if (view === "custom") {
    components = [
      ...components,
      ...views[view].flatMap(({ routes }) => routes)
    ];
  } else {
    components = [...components, ...views[view].routes];
  }
}

components = Object.fromEntries(
  components.map(({ component }) => [component.name, component])
);

export { views, components, viewAddModuleComponents, viewEditModuleComponents };
