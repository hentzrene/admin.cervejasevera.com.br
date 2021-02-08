<template lang="pug">
div
  component(v-if="!loading", :is="component", :data="module")
  v-sheet.d-flex.justify-center.align-center(
    v-else,
    height="calc(100vh - 48px)",
    width="100%",
    color="transparent"
  )
    v-progress-circular(:size="50", color="secondary", indeterminate)
</template>

<script>
import { views, components } from "@/modules/views";
import pathToRegexp from "path-to-regexp";

export default {
  data: () => ({
    component: null,
    loading: false,
    gettedModules: false,
  }),
  computed: {
    modules() {
      return this.$rest("modules").list;
    },
    moduleKey() {
      return this.$route.params.module;
    },
    module() {
      return this.modules.find(({ key }) => key === this.moduleKey);
    },
    sub() {
      return this.$route.params.sub;
    },
  },
  methods: {
    async get() {
      this.loading = true;
      if (!this.gettedModules) {
        await this.$rest("modules").get();
      }

      return this.renderModule().then(() => (this.loading = false));
    },
    mountRoutes(routes, module) {
      return routes.map((r) => {
        let mountRoute = { ...r };

        mountRoute.name = mountRoute.name.replace("{name}", module.name);
        mountRoute.path = mountRoute.path.replace("{key}", module.key);

        return mountRoute;
      });
    },
    async renderModule() {
      if (!this.module) {
        this.$router.push("/error404");
        return;
      } else {
        await this.$rest("modules").get({ id: this.module.id });
      }

      let routes = [];

      if (this.module.viewKey === "custom") {
        for (let view of views.custom) {
          if (view.key === this.moduleKey) {
            routes = view.routes;
            break;
          }
        }
      } else {
        routes = this.mountRoutes(
          views[this.module.viewKey].routes,
          this.module
        );
      }

      for (let { path, component } of routes) {
        const regExp = new RegExp(pathToRegexp(path));
        if (regExp.test(this.$route.path)) {
          this.component = component.name;
          break;
        }
      }

      this.gettedModules = true;
    },
  },
  watch: {
    async moduleKey() {
      await this.get();
    },
    async sub() {
      await this.get();
    },
  },
  async created() {
    await this.get();
  },
  components,
};
</script>

<style>
</style>
