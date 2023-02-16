<template>
  <module-template title="Adicionar módulo">
    <v-card class="pa-4 pb-0 rounded-t-0" outlined="outlined" dark="dark">
      <v-form class="pt-4" ref="form">
        <v-row>
          <v-col class="py-0" cols="12" sm="6" md="12" lg="3">
            <v-text-field
              v-model="name"
              :rules="[rules.required]"
              label="Nome"
              outlined="outlined"
              dense="dense"
            ></v-text-field>
          </v-col>
          <v-col class="py-0" cols="12" sm="6" md="4" lg="3">
            <v-select
              v-model="viewSelected"
              :items="views"
              :rules="[rules.required]"
              label="Tipo"
              outlined="outlined"
              dense="dense"
            ></v-select>
          </v-col>
          <v-col class="py-0" cols="12" sm="6" md="4" lg="3">
            <v-text-field
              v-model="icon"
              :rules="[rules.required]"
              :append-icon="icon"
              label="Ícone"
              outlined="outlined"
              dense="dense"
            ></v-text-field>
          </v-col>
          <v-col class="py-0" cols="12" sm="6" md="4" lg="3">
            <v-text-field
              v-model="key"
              :rules="[rules.required, rules.alphaNumUnderline]"
              label="Chave"
              outlined="outlined"
              dense="dense"
            ></v-text-field>
          </v-col>
        </v-row>
        <component
          v-if="viewSelected"
          :is="viewSelected + '-add'"
          ref="view"
        ></component>
        <div
          class="d-flex justify-end py-4"
          v-if="viewSelected && viewSelected !== 'custom'"
        >
          <v-btn
            class="text-none"
            @click="add"
            color="secondary"
            depressed="depressed"
            :loading="loading"
            :disabled="loading"
            >Adicionar</v-btn
          >
        </div>
      </v-form>
    </v-card>
  </module-template>
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import { required, alphaNumUnderline } from "@/components/forms/rules";
import { viewAddModuleComponents as addComponents } from "@/modules/views";

export default {
  name: "ModuleAdd",
  data: () => ({
    name: null,
    icon: null,
    key: null,
    viewSelected: null,
    rules: {
      required,
      alphaNumUnderline,
    },
    loading: false,
  }),
  computed: {
    views() {
      return this.$rest("modulesViews").list.map(({ key, name }) => ({
        text: name,
        value: key,
      }));
    },
  },
  methods: {
    add() {
      const form = this.$refs.form;
      const view = this.$refs.view;
      let formValidity = null;
      let viewValidity = null;

      if (form) formValidity = form.validate();
      if (view) viewValidity = view.validate();

      if (!formValidity || !viewValidity) return;

      const viewId = this.$rest("modulesViews").list.find(
        ({ key }) => key === this.viewSelected
      ).id;

      this.loading = true;
      this.$rest("modules")
        .post({
          data: {
            name: this.name,
            icon: this.icon,
            key: this.key,
            viewId,
            ...view.data,
          },
        })
        .then(() => this.$router.push("/modules"))
        .finally(() => {
          this.loading = false;
        });
    },
  },
  beforeCreate() {
    this.$rest("modulesViews").get();
  },
  created() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }
  },
  components: {
    ModuleTemplate,
    ...Object.fromEntries(
      Object.entries(addComponents).map((c) => {
        c[0] = c[0].toLowerCase() + "-add";
        return c;
      })
    ),
  },
};
</script>

<style>
.v-input__icon.v-input__icon--append {
  overflow: hidden;
}
</style>
