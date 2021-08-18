const mutations = {
  updateList: (state, { data, keepCache, cacheItemKey }) => {
    if (!Array.isArray(data)) {
      window.console.warn(
        "VuexRest: a mutação updateList deve ter um array como seu parâmetro de dados."
      );
      return;
    }
    if (keepCache && state.list.length) {
      for (let item of data) {
        if (state.list.find((v) => v[cacheItemKey] == item[cacheItemKey]))
          continue;

        state.list.push(item);
      }
    } else {
      state.list = data;
    }
  },
  refreshItem: (state, id) => {
    const item = state.list.find((v) => v.id == id);
    if (item) state.item = item;
  },
  updateItem: (state, { id, data, keepCache }) => {
    state.itemId = id;
    state.item = data;

    const item = state.list.find((v) => v.id == id);

    if (!item) {
      state.list.push({ ...data, id });
    } else {
      for (let prop in data) {
        if (keepCache && item[prop]) continue;

        item[prop] = data[prop];
      }
    }
  },
  setProperty: (state, { id, prop, data }) => {
    const item = state.list.find((v) => v.id == id);
    if (item) item[prop] = data;
  },
  remove: (state, id) =>
    state.list.splice(
      state.list.findIndex((v) => v.id == id),
      1
    ),
  function: (state, data) => data(state),
};

export default mutations;
