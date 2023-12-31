<template>
  <v-app>
    <template v-if="!checking">
      <main-nav v-if="!showHeader"></main-nav>
      <v-main>
        <router-view></router-view>
      </v-main>
    </template>
    <v-sheet
      class="d-flex justify-center align-center"
      v-else
      color="transparent"
      height="100%"
      width="100%"
    >
      <loading></loading>
    </v-sheet>
    <float-alert :value="lastRequestError" color="red">
      <span>{{ lastRequestError }}</span>
    </float-alert>
    <float-alert :value="lastRequestSuccess" color="green">
      <span>{{ lastRequestSuccess }}</span>
    </float-alert>
  </v-app>
</template>

<script>
import MainNav from "@/components/shell/main-nav/Index.vue";
import FloatAlert from "@/components/tools/FloatAlert";
import Loading from "@/components/tools/Loading";
import { http } from "./plugins/vuex-rest";
import { mapState } from "vuex";

export default {
  data: () => ({
    checking: true,
  }),
  computed: {
    ...mapState(["lastRequestError", "lastRequestSuccess"]),
    showHeader() {
      return ["/error404", "/entrar", "/setup"].includes(this.$route.path);
    },
  },
  beforeCreate() {
    http.interceptors.request.use(
      (config) => {
        this.$store.dispatch("startRequest");
        config.headers["Authorization"] = localStorage.getItem(`token`) || "";
        return config;
      },
      (error) =>
        this.$store.dispatch("connectionStatusChange", { ok: false, error }) &&
        Promise.reject(error),
      { synchronous: true }
    );

    http.interceptors.response.use(
      (response) =>
        this.$store.dispatch("endRequest", response.data || {}) && response,
      (error) => {
        if (error.response.status === 303) {
          this.$router.push(new URL(error.response.data.redirect).pathname);
          return this.$store.dispatch("endRequest", {});
        } else if (
          error.response.status === 403 ||
          error.response.status === 401
        ) {
          this.$auth.setStateProp("on", false);
          this.$router.replace("/entrar");
          return this.$store.dispatch("endRequest", {});
        } else {
          return (
            this.$store.dispatch("endRequest", error.response.data || {}) &&
            Promise.reject(error)
          );
        }
      },
      { synchronous: true }
    );
  },
  created() {
    if (!this.installed) {
      if (this.$route.path !== "/setup") this.$router.replace(`/setup`);

      this.checking = false;
    } else if (!this.$auth.on) {
      this.checking = true;

      const token = window.localStorage.getItem(`token`);
      const login = ["/entrar"].includes(this.$route.path);

      if (token) {
        this.$auth
          .checkIn()
          .then((data) => {
            this.$store.dispatch("setInfoUser", data);
            if (login) this.$router.replace(`/`);
          })
          .catch(() => !login && this.$router.replace(`/entrar`))
          .finally(() => (this.checking = false));
      } else if (!login) {
        this.checking = false;
        this.$router.replace(`/entrar`);
      } else {
        this.checking = false;
      }
    }
  },
  components: {
    MainNav,
    FloatAlert,
    Loading,
  },
};
</script>

<style>
@import "./style.css";
</style>
