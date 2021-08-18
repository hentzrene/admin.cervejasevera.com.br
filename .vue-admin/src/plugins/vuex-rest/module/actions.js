import { http } from "../index";
import camelCaseToHyphen from "../utils/camelCaseToHyphen";

const createURL = (group, { id, prop, params }) => {
  let url = `/${group}`;
  params = params && new URLSearchParams(params).toString();

  if (id) url += "/" + id;
  if (prop) url += "/" + camelCaseToHyphen(prop);
  if (params) url += "?" + params;

  return url;
};

const actions = {
  get: async (
    { state, commit },
    { id, params, save = true, keepCache = false, cacheItemKey = "id" }
  ) => {
    if (id) commit("refreshItem", id);

    const url = createURL(state.group, { id, params });

    const { data } = await http.get(url);

    if (data) {
      if (save && typeof save === "function")
        commit("function", (state) => save(state, data));
      else if (id) commit("updateItem", { id, data, keepCache, cacheItemKey });
      else commit("updateList", { data, keepCache, cacheItemKey });
    }

    return data;
  },
  post: async ({ state, commit }, { data, params, save = true }) => {
    let url = createURL(state.group, { data, params });

    if (data && typeof data !== "object") data = { value: data };

    const res = await http.post(url, data);
    if (save && typeof save === "function")
      commit("function", (state) => save(state));
    else if (res.data) commit("updateList", { data: res.data });
    return res.data;
  },
  put: async ({ state, commit }, { id, prop, data, params, save = true }) => {
    if (id && prop) {
      let data_ = state.list.find((v) => v.id == id);
      if (data_) data_ = data_[prop];

      if (data == data_) return;
    } else if (id) {
      const data_ = state.list.find((v) => v.id == id);
      if (data == data_) return;
    }

    let url = createURL(state.group, { id, prop, params });

    if (data instanceof FormData) {
      data = Object.fromEntries(data.entries());
    } else if (typeof data !== "object") {
      data = { value: data };
    }

    await http.put(url, data);

    if (save && typeof save === "function")
      commit("function", (state) => save(state));
    else if (id && prop) {
      commit("setProperty", {
        id,
        prop,
        data: typeof data === "object" ? data.value : data,
      });
    } else if (id) {
      commit("updateItem", { id, data });
    }

    return true;
  },
  upload: async (
    { state, commit },
    { id, prop, file, params, data, onUploadProgress, save = true }
  ) => {
    let url = createURL(state.group, {
      id,
      prop,
      params,
    });

    const config = {
      onUploadProgress,
    };

    const _data = new FormData();
    _data.append(prop, file);

    for (let key in data) {
      _data.append(key, data[key]);
    }

    const res = await http.post(url, _data, config);

    if (!res.data.file) {
      window.console.warn(
        `VuexRest: a requisição para a rota "${url}" não teve como resposta um
        objeto json com a propiedade file especificando a url para o caminho
        do arquivo que foi feito upload.`
      );
    } else {
      if (save && typeof save === "function")
        commit("function", (state) => save(state, res.data));
      else if (id && prop) {
        commit("setProperty", {
          id,
          prop,
          data: res.data.file,
        });
      } else if (id) {
        commit("updateItem", { id, data: res.data.file });
      }
    }

    return res.data;
  },
  delete: async ({ state, commit }, { id, params, save = true }) => {
    const url = createURL(state.group, { id, params });
    await http.delete(url);

    if (save && typeof save === "function")
      commit("function", (state) => save(state));
    else if (id) commit("remove", id);

    return true;
  },
};

export default actions;
