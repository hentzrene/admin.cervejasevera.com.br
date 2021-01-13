import Vue from "vue";
import Vuetify from "vuetify/lib";

Vue.use(Vuetify);

export default new Vuetify({
  icons: {
    iconfont: "fa"
  },
  theme: {
    options: {
      customProperties: true
    },
    themes: {
      light: {
        primary: "#000000",
        secondary: "#e93751",
        accent: "#00a7bc"
      }
    }
  }
});
