<template lang="pug">
module-template(:title="`Alterar módulo \"${module.name}\"`")
  template(slot="toolbar")
    export-module-button(v-if="moduleId", :module-id="moduleId")
  v-card.pa-4.pb-0.rounded-t-0(outlined, dark)
    v-form.pt-4(ref="form")
      v-row
        v-col.py-0(cols=12, sm=6, md=12, lg=3)
          v-text-field(
            @keydown.enter="updateProperty($event, 'name')",
            @blur="updateProperty($event, 'name')",
            :value="module.name",
            :rules="[rules.required]",
            :loading="updating.name",
            label="Nome",
            outlined,
            dense
          )
        v-col.py-0(cols=12, sm=6, md=4, lg=3)
          v-text-field(
            @keydown.enter="updateProperty($event, 'icon')",
            @blur="updateProperty($event, 'icon')",
            :value="module.icon",
            :rules="[rules.required]",
            :loading="updating.icon",
            label="Ícone",
            outlined,
            dense
          )
    component(v-if="loaded", :is="module.viewKey + '-edit'", :data="module")
    v-sheet.d-flex.justify-center.align-center.py-8(
      v-else,
      width="100%",
      color="transparent"
    )
      loading
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import Loading from "@/components/tools/Loading";
import ExportModuleButton from "@/components/buttons/ExportModule";
import { viewEditModuleComponents as editComponents } from "@/modules/views";
import { required } from "@/components/forms/rules";

export default {
  data: () => ({
    rules: {
      required,
    },
    loaded: false,
    updating: {
      name: false,
      icon: false,
    },
  }),
  computed: {
    modules() {
      return this.$rest("modules").list;
    },
    moduleId() {
      return this.$route.params.module;
    },
    module() {
      return this.modules.find(({ id }) => id == this.moduleId) || {};
    },
  },
  methods: {
    updateProperty({ target: { value } }, prop) {
      if (this.module[prop] === value) return false;

      this.updating[prop] = true;
      this.$rest("modules")
        .put({
          id: this.moduleId,
          data: { value },
          prop,
        })
        .finally(() => {
          this.updating[prop] = false;
        });
    },
  },
  created() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }

    this.loaded = false;
    this.$rest("modules")
      .get({ id: this.moduleId })
      .finally(() => (this.loaded = true));
  },
  components: {
    ModuleTemplate,
    Loading,
    ExportModuleButton,
    ...Object.fromEntries(
      Object.entries(editComponents).map((c) => {
        c[0] = c[0].toLowerCase() + "-edit";
        return c;
      })
    ),
  },
};
</script>

<style></style>