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

const config = JSON.parse(document.getElementById("config").textContent);
const server =
  process.env.NODE_ENV === "development"
    ? process.env.VUE_APP_SERVER + config.prefixPath
    : location.origin + config.prefixPath;

Vue.config.productionTip = false;

Vue.use(VuexRest, {
  base: server + "/rest",
});

console.log(server);

Vue.mixin({
  data: () => ({
    server,
    files: server,
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
