export default {
  connectionStatusChange: ({ commit }, { ok, error }) => {
    window.console.warn("VuexRest: ", { ok, error });
    commit("function", state => {
      state.connectionOk = ok;
      state.connectionError = error;
    });
  },
  startRequest: ({ commit, dispatch, state }) => {
    if (!state.connectionOk) dispatch("connectionStatusChange", { ok: true });

    commit("function", state => {
      state.loading = true;
      // state.lastRequestStatus = null
      // state.lastRequestError = null
      // state.lastRequestSuccess = null
    });
  },
  endRequest: ({ commit }, { status, error, success }) => {
    if (status) window.console.warn("VuexRest: ", { status, error, success });

    commit("function", state => {
      state.loading = false;
      if (status) {
        state.lastRequestStatus = status;
        state.lastRequestError = error;
        state.lastRequestSuccess = success;
      }
    });

    if (status) {
      window.setTimeout(() => {
        commit("function", state => {
          state.lastRequestStatus = null;
          state.lastRequestError = null;
          state.lastRequestSuccess = null;
        });
      }, 4000);
    }
  },
  setInfoUser({ commit }, data) {
    commit("function", state => {
      for (let k in data) {
        state.user[k] = data[k];
      }

      state.user = { ...state.user };
    });
  }
};
