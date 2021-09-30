import Vue from "vue";
import App from "./App.vue";
import router from "./router";
import store from "./store";
import vuetify from "./plugins/vuetify";
import VuexRest from "./plugins/vuex-rest";
import VueGrid from "./plugins/vue-grid/src";
import CKEditor from "@ckeditor/ckeditor5-vue2";
import "./plugins/simple-auth";

Vue.use(VueGrid);
Vue.use(CKEditor);

const server =
  process.env.NODE_ENV === "development"
    ? process.env.VUE_APP_SERVER
    : location.origin;
const config = JSON.parse(document.getElementById("config").textContent);

Vue.config.productionTip = false;

Vue.use(VuexRest, {
  base: server + "/admin/rest",
});

Vue.mixin({
  data: () => ({
    server,
    files: server + "/admin",
    ...config,
    version: process.env.VERSION,
  }),
});

new Vue({
  router,
  vuetify,
  store,
  render: (h) => h(App),
}).$mount("#app");
