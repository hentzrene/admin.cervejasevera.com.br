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
      .d-flex.flex-wrap(v-if="account.type != 1")
        v-checkbox.mx-2(
          v-for="({ id, name }, i) in modules",
          v-model="permissions",
          :key="i",
          :label="name",
          :value="id"
        )
      .d-flex.justify-end
        v-btn.text-none(@click="send", color="secondary", depressed) Salvar
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import { required, email } from "@/components/forms/rules.js";

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
      return this.$rest("modulesBasic").list;
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
  beforeCreate() {
    this.$rest("modulesBasic").get();
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

<style>
</style>
