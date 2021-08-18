import components from "./components/index.js";

const install = (Vue) => {
  for (let name in components) Vue.component(name, components[name]);
};

export default install;
