<template lang="pug">
v-app
  template(v-if="!checking")
    main-nav(
      v-if="!['/admin/error404', '/admin/entrar', '/admin/setup'].includes($route.path)"
    )
    v-main
      router-view
  v-sheet.d-flex.justify-center.align-center(
    v-else,
    color="transparent",
    height="100%",
    width="100%"
  )
    v-progress-circular(:size="50", color="secondary", indeterminate)
  float-alert(:value="lastRequestError", color="red")
    span {{ lastRequestError }}
  float-alert(:value="lastRequestSuccess", color="green")
    span {{ lastRequestSuccess }}
</template>

<script>
import MainNav from "@/components/shell/main-nav/Index.vue";
import FloatAlert from "@/components/tools/FloatAlert";
import { http } from "./plugins/vuex-rest";
import { mapState } from "vuex";

export default {
  data: () => ({
    checking: true,
  }),
  computed: {
    ...mapState(["lastRequestError", "lastRequestSuccess"]),
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
        Promise.reject(error)
    );

    http.interceptors.response.use(
      (response) =>
        this.$store.dispatch("endRequest", response.data) && response,
      (error) => {
        if (error.response.status === 303) {
          this.$router.push(new URL(error.response.data.redirect).pathname);
          return this.$store.dispatch("endRequest", {});
        } else {
          return (
            this.$store.dispatch("endRequest", error.response.data) &&
            Promise.reject(error)
          );
        }
      }
    );
  },
  created() {
    if (!this.installed) {
      if (this.$route.path !== "/admin/setup")
        this.$router.replace(`/admin/setup`);

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
            if (login) this.$router.replace(`/admin`);
          })
          .catch(() => !login && this.$router.replace(`/admin/entrar`))
          .finally(() => (this.checking = false));
      } else if (!login) {
        this.checking = false;
        this.$router.replace(`/admin/entrar`);
      } else {
        this.checking = false;
      }
    }
  },
  components: {
    MainNav,
    FloatAlert,
  },
};
</script>

<style>
@import "./style.css";
</style>
