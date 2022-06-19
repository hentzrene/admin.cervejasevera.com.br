<template lang="pug">
module-template(title="Contas / Adicionar", width="800px", max-width="100%")
  v-card.pa-4.rounded-t-0(outlined, dark)
    v-form.pt-4(ref="form")
      v-row
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            label="Nome",
            name="name",
            :rules="[rules.required]",
            outlined,
            dense
          )
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            label="E-mail",
            name="email",
            :rules="[rules.required, rules.email]",
            outlined,
            dense
          )
      v-text-field(
        label="Senha",
        name="password",
        :rules="[rules.required]",
        outlined,
        dense
      )
      div
        div.grey--text.text--lighten-1.font-weight-bold Permissões
        v-treeview(
          v-model="userPermissions"
          :items="modulesPermissions"
          selectable
          open-all
        )
      .d-flex.justify-end
        v-btn.text-none(@click="send", color="secondary", depressed) Adicionar
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import { required, email } from "@/components/forms/rules.js";
import { groupBy } from "../../utils";

export default {
  name: "AccountAdd",
  data: () => ({
    rules: {
      required,
      email,
    },
    userPermissions: [],
  }),
  computed: {
    modules() {
      return this.$rest("modules").list.map((mod) => {
        const moduleView = this.modulesViews.find(
          (moduleView) => moduleView.key === mod.viewKey
        );

        mod.permissions = moduleView && moduleView.permissions;

        return mod;
      });
    },
    modulesViews() {
      return this.$rest("modulesViews").list;
    },
    modulesPermissions() {
      let menu = this.modules.map((mod) => {
        let cloneMod = { ...mod };
        if (!cloneMod.menuId) cloneMod.menuTitle = "$";
        if (!cloneMod.submenuId) cloneMod.submenuTitle = "$";

        return cloneMod;
      });

      const groupedMenu = groupBy(menu, "menuTitle");

      let groupedSubmenu = {};
      for (let id in groupedMenu) {
        groupedSubmenu[id] = groupBy(groupedMenu[id], "submenuTitle");
      }

      const tree = Object.entries(groupedSubmenu)
        .map(([menuKey, menuChildren]) => {
          const children = Object.entries(menuChildren)
            .map(([submenuKey, submenuChildren]) => {
              const children = submenuChildren.map((mod) => {
                const children = mod.permissions.map(({ id, title }) => ({
                  id: `${mod.id}:${id}`,
                  name: title,
                }));

                return {
                  id: mod.id,
                  name: mod.name,
                  children,
                };
              });

              if (submenuKey === "$") {
                return children;
              }

              return [
                {
                  id: Symbol(),
                  name: submenuKey,
                  children,
                },
              ];
            })
            .flat();

          if (menuKey === "$") {
            return children;
          }

          return [
            {
              id: Symbol(),
              name: menuKey,
              children,
            },
          ];
        })
        .flat();

      return tree;
    },
  },
  methods: {
    send() {
      const form = this.$refs.form;
      if (form.validate()) {
        const data = Object.fromEntries(new FormData(form.$el).entries());

        data.permissions = this.userPermissions.map((permission) => {
          const [modules_id, modules_views_permissions_id] =
            permission.split(":");

          return {
            modules_id,
            modules_views_permissions_id,
          };
        });

        this.$rest("accounts")
          .post({ data })
          .then(() => this.$router.push("/accounts"));
      }
    },
  },
  beforeCreate() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }

    this.$rest("modulesViews").get();
  },
  components: {
    ModuleTemplate,
  },
};
</script>

<style></style>
