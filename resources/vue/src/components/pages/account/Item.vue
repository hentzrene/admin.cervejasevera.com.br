<template lang="pug">
module-template(title="Contas / Alterar", width="800px")
  v-card.pa-4.rounded-t-0(outlined, dark)
    v-form.pt-4(ref="form")
      v-row
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            :value="account.name",
            :rules="[rules.required]",
            label="Nome",
            name="name",
            outlined,
            dense
          )
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            :value="account.email",
            :rules="[rules.required, rules.email]",
            label="E-mail",
            name="email",
            outlined,
            dense
          )
      v-text-field(label="Senha", name="password", outlined, dense)
      div(v-if="account.type != 1")
        template(v-for="(submenus, menuTitle) in groupedModules")
          .d-flex.flex-wrap(v-if="menuTitle === '$'")
            v-checkbox.mx-2(
              v-for="({ id, name }, i) in groupedModules.$.$",
              v-model="permissions",
              :key="i",
              :label="name",
              :value="id"
            )
          v-card.mb-3.pa-3.pb-0.primary.lighten-2(v-else, outlined)
            .font-weight-bold {{ menuTitle }}
            div
              template(v-for="(items, submenuTitle) in submenus")
                .d-flex.flex-wrap(v-if="submenuTitle === '$'")
                  v-checkbox.mx-2(
                    v-for="({ id, name }, i) in submenus[submenuTitle]",
                    v-model="permissions",
                    :key="i",
                    :label="name",
                    :value="id"
                  )
                v-card.pa-3.mb-3(v-else)
                  .font-weight-bold {{ submenuTitle }}
                  .d-flex.flex-wrap.ml-2
                    v-checkbox.mx-2(
                      v-for="({ id, name }, i) in items",
                      v-model="permissions",
                      :key="id",
                      :label="name",
                      :value="id"
                    )
      .d-flex.justify-end
        v-btn.text-none(@click="send", color="secondary", depressed) Salvar
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import { required, email } from "@/components/forms/rules.js";
import { groupBy } from "../../utils";

export default {
  name: "AccountItem",
  data: () => ({
    rules: {
      required,
      email,
    },
    permissions: [],
  }),
  computed: {
    accountId() {
      return parseInt(this.$route.params.account);
    },
    account() {
      return this.$rest("accounts").item;
    },
    modules() {
      return this.$rest("modules").list;
    },
    groupedModules() {
      let menu = this.modules.map((mod) => {
        let cloneMod = { ...mod };
        if (!cloneMod.menuId) cloneMod.menuTitle = "$";
        if (!cloneMod.submenuId) cloneMod.submenuTitle = "$";

        return cloneMod;
      });

      menu = groupBy(menu, "menuTitle");

      for (let id in menu) {
        menu[id] = groupBy(menu[id], "submenuTitle");
      }

      for (let menuKey in menu) {
        for (let submenuKey in menu[menuKey]) {
          menu[menuKey][submenuKey] = menu[menuKey][submenuKey].map(
            ({ id, name }) => ({
              id,
              name,
            })
          );
        }
      }

      return menu;
    },
  },
  methods: {
    send() {
      const form = this.$refs.form;
      if (form.validate()) {
        const data = Object.fromEntries(new FormData(form.$el).entries());

        data.permissions = this.permissions;

        this.$rest("accounts")
          .put({ id: this.accountId, data })
          .then(() => {
            if (this.accountId == this.$store.state.user.id) {
              this.$store.dispatch("setInfoUser", this.$rest("accounts").item);
            }
            this.$rest("modules").get();
            this.$router.push("/accounts");
          });
      }
    },
  },
  created() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }

    this.$rest("accounts")
      .get({ id: this.accountId })
      .then(({ permissions }) => {
        this.permissions = permissions || [];
      })
      .catch(() => this.$router.replace("/accounts"));
  },
  components: {
    ModuleTemplate,
  },
};
</script>

<style></style>
