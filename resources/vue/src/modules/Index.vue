<template>
  <div>
    <component
      v-if="!loading"
      :is="moduleComponentName"
      :data="module"
    ></component>
    <v-sheet
      class="d-flex justify-center align-center"
      v-else
      height="calc(100vh - 48px)"
      width="100%"
      color="transparent"
    >
      <loading></loading>
    </v-sheet>
  </div>
</template>

<script>
import Loading from "@/components/tools/Loading";
import { views, components } from "@/modules/views";
import pathToRegexp from "path-to-regexp";

export default {
  data: () => ({
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
    sub() {
      return this.$route.params.sub;
    },
    module() {
      return this.modules.find(({ key }) => key === this.moduleKey);
    },
    moduleViewRoutes() {
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

      return routes;
    },
    moduleComponentName() {
      let name = null;

      for (let { path, component } of this.moduleViewRoutes) {
        const regExp = new RegExp(pathToRegexp(path));

        if (regExp.test(this.$route.path)) {
          name = component.name;
          break;
        }
      }

      return name;
    },
  },
  methods: {
    mountRoutes(routes, module) {
      return routes.map((r) => {
        let mountRoute = { ...r };

        mountRoute.name = mountRoute.name.replace("{name}", module.name);
        mountRoute.path = mountRoute.path.replace("{key}", module.key);

        return mountRoute;
      });
    },
    async get() {
      if (this.gettedModules) {
        return;
      }

      this.loading = true;

      await this.$rest("modules").get();

      this.loading = false;
    },
  },
  watch: {
    async $route() {
      if (!this.$auth.on) return;

      await this.get();
    },
  },
  async created() {
    await this.get();
  },
  components: {
    Loading,
    ...components,
  },
};
</script>
