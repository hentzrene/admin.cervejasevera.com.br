<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="330px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title mb-4 text-center">Definir menu</div>
      <v-form ref="form">
        <v-select
          class="mb-3"
          v-model="menuSelected"
          :items="menuItems"
          label="Menu"
          name="menuId"
          outlined="outlined"
          dense="dense"
          hide-details="hide-details"
        ></v-select>
        <v-select
          class="mb-3"
          :value="submenuId"
          :items="submenusItems"
          label="Submenu"
          name="submenuId"
          outlined="outlined"
          dense="dense"
          hide-details="hide-details"
        ></v-select>
      </v-form>
      <v-btn
        class="text-none"
        @click="save"
        :disabled="loading"
        color="secondary"
        block="block"
        depressed="depressed"
        >Salvar</v-btn
      >
    </v-card>
    <v-overlay v-if="value" v-model="loading">
      <loading></loading>
    </v-overlay>
  </v-dialog>
</template>

<script>
import Loading from "@/components/tools/Loading";

export default {
  props: {
    value: Boolean,
    menus: Array,
    moduleItem: Object,
  },
  data: () => ({
    loading: false,
    menuSelected: null,
  }),
  computed: {
    menuId() {
      return this.moduleItem.menuId;
    },
    submenuId() {
      return this.moduleItem.submenuId;
    },
    menuItems() {
      const r = this.$rest("modulesMenu").list.map(({ id, title }) => ({
        text: title,
        value: id,
      }));

      r.unshift({ text: "(Nenhum)", value: null });

      return r;
    },
    submenusItems() {
      let submenusItems = [{ text: "(Nenhum)", value: null }];

      if (!this.menuSelected) {
        return submenusItems;
      }

      const menu = this.$rest("modulesMenu").list.find(
        (menu) => menu.id === this.menuSelected
      );

      if (menu) {
        menu.submenus.forEach(({ id, title }) => {
          submenusItems.push({ text: title, value: id });
        });
      }

      return submenusItems;
    },
  },
  methods: {
    save() {
      this.loading = true;

      const form = this.$refs.form;

      const data = new FormData(form.$el);

      this.$rest("modules")
        .put({
          id: this.moduleItem.id,
          prop: "menu",
          data,
        })
        .then(() => {
          this.loading = false;

          const menuId = data.get("menuId");
          const submenuId = data.get("submenuId");

          if (menuId) {
            const menu = this.menus.find(({ id }) => menuId == id);

            this.moduleItem.menuId = menuId;
            this.moduleItem.menuTitle = menu.title;

            if (submenuId) {
              const submenu = menu.submenus.find(({ id }) => submenuId == id);

              this.moduleItem.submenuId = submenuId;
              this.moduleItem.submenuTitle = submenu.title;
            } else {
              this.moduleItem.submenuId = null;
              this.moduleItem.submenuTitle = null;
            }
          } else {
            this.moduleItem.menuId = null;
            this.moduleItem.menuTitle = null;
            this.moduleItem.submenuId = null;
            this.moduleItem.submenuTitle = null;
          }

          this.$emit("input", false);
        });
    },
  },
  watch: {
    moduleItem() {
      this.menuSelected = this.menuId;
    },
  },
  created() {
    this.menuSelected = this.menuId;
  },
  components: {
    Loading,
  },
};
</script>
