import axios from "axios";
import { createModule } from "./module";
import { camelCaseToHyphen, validateOptions } from "./utils";
import moduleGetters from "./module/getters";
import moduleActions from "./module/actions";

const http = axios.create();

const getters = Object.keys(moduleGetters);
const actions = Object.keys(moduleActions);

const VuexRest = {
  install: (Vue, { base }) => {
    http.defaults.baseURL = base || "/";

    Vue.prototype.$rest = function (module) {
      if (typeof module !== "string")
        throw new TypeError("O parâmetro <module> deve ser uma string.");

      const store = this.$store;
      const callAction = (action, data) => {
        validateOptions(data || {});
        return store.dispatch(`${"_rest_" + module}/${action}`, data || {});
      };

      if (!store.hasModule("_rest_" + module)) {
        store.registerModule(
          "_rest_" + module,
          createModule(camelCaseToHyphen(module))
        );
      }

      const { item, list } = store.state["_rest_" + module];

      const rest = {
        item,
        list,
        getters: {},
      };

      for (let getter of getters)
        rest.getters[getter] = store.getters[`${"_rest_" + module}/${getter}`];

      for (let action of actions)
        rest[action] = (data) => callAction(action, data);

      return rest;
    };
  },
};

export { http };
export default VuexRest;
