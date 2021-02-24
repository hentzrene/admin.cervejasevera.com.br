import Vue from "vue";
import VueRouter from "vue-router";
import Login from "@/components/pages/auth/Login";
import Home from "@/components/pages/Home";
import Setup from "@/components/pages/Setup";
import ModuleList from "@/components/pages/modules/List.vue";
import ModuleAdd from "@/components/pages/modules/Add.vue";
import ModuleEdit from "@/components/pages/modules/Edit.vue";
import AccountList from "@/components/pages/account/List.vue";
import AccountAdd from "@/components/pages/account/Add.vue";
import AccountItem from "@/components/pages/account/Item.vue";
import Email from "@/components/pages/Email.vue";
import Error404 from "@/components/pages/Error404.vue";
import Module from "@/modules/Index.vue";

Vue.use(VueRouter);

const name = "Painel de Administração";

const routes = [
  {
    name,
    path: "/",
    component: Home
  },
  {
    name: `Instalar - ${name}`,
    path: "/setup",
    component: Setup
  },
  {
    name: `Entrar - ${name}`,
    path: "/entrar",
    component: Login
  },
  {
    name: "Contas",
    path: "/accounts",
    component: AccountList
  },
  {
    name: "Adicionar conta",
    path: "/accounts/adicionar",
    component: AccountAdd
  },
  {
    name: "Alterar conta",
    path: "/accounts/:account",
    component: AccountItem
  },
  {
    name: "E-mail",
    path: `/email`,
    component: Email
  },
  {
    name: "Módulos",
    path: `/modules`,
    component: ModuleList
  },
  {
    name: "Adicionar módulo",
    path: `/modules/adicionar`,
    component: ModuleAdd
  },
  {
    name: "Alterar módulo",
    path: `/modules/:module`,
    component: ModuleEdit
  },
  {
    name: "Error404",
    path: "/error404",
    component: Error404
  },
  {
    name: `Módulo - ${name}`,
    path: "/:module",
    component: Module
  },
  {
    name: `Módulo - ${name}`,
    path: "/:module/:sub",
    component: Module
  },
  {
    path: "*",
    beforeEnter: (to, from, next) => {
      next("/error404");
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
