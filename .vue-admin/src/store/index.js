import Vue from "vue";
import Vuex from "vuex";
import actions from "./actions";
import mutations from "./mutations";

const store = {
  state: {
    connectionOk: null,
    connectionError: null,
    loading: false,
    lastRequestStatus: null,
    lastRequestError: null,
    lastRequestSuccess: null,
    user: {},
  },
  mutations,
  actions,
};

Vue.use(Vuex);

export default new Vuex.Store(store);
