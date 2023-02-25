import custom from "./components/custom";
import table from "./components/table";
import item from "./components/item";
import newsletter from "./components/newsletter";

const views = {
  custom,
  table,
  item,
  newsletter,
};

const { viewAddModuleComponents, viewEditModuleComponents } = Object.entries(
  views
).reduce(
  (moduleConfigComponents, [name, view]) => {
    moduleConfigComponents.viewAddModuleComponents[name] =
      view.moduleConfig.components.add;
    moduleConfigComponents.viewEditModuleComponents[name] =
      view.moduleConfig.components.edit;

    return moduleConfigComponents;
  },
  { viewAddModuleComponents: {}, viewEditModuleComponents: {} }
);

let components = [];

for (let view in views) {
  if (view === "custom") {
    components = [
      ...components,
      ...views[view].modules.flatMap(({ routes }) => routes),
    ];
  } else {
    components = [...components, ...views[view].routes];
  }
}

components = Object.fromEntries(
  components.map(({ component }) => [component.name, component])
);

export { views, components, viewAddModuleComponents, viewEditModuleComponents };
