import Vue from "vue";
import VueRouter from "vue-router";
import Login from "@/components/pages/auth/Login";
import Home from "@/components/pages/Home";
import Setup from "@/components/pages/Setup";
import Error404 from "@/components/pages/Error404.vue";
import Module from "@/modules/Index.vue";

Vue.use(VueRouter);

const name = "Painel de Administração";

const routes = [
  {
    path: "/",
    redirect: "/admin"
  },
  {
    name,
    path: "/admin",
    component: Home
  },
  {
    name: `Instalar - ${name}`,
    path: "/admin/setup",
    component: Setup
  },
  {
    name: `Entrar - ${name}`,
    path: "/admin/entrar",
    component: Login
  },
  {
    name: "Error404",
    path: "/admin/error404",
    component: Error404
  },
  {
    name: `Módulo - ${name}`,
    path: "/admin/:module",
    component: Module
  },
  {
    name: `Módulo - ${name}`,
    path: "/admin/:module/:sub",
    component: Module
  },
  {
    path: "*",
    beforeEnter: (to, from, next) => {
      next("/admin/error404");
    }
  }
];

const router = new VueRouter({
  mode: "history",
  base: process.env.BASE_URL,
  routes
});

router.afterEach(to => {
  document.title = to.name;
  window.scrollTo(0, 0);
});

export default router;