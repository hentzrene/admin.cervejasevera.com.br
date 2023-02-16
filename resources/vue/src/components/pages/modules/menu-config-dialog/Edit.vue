<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="330px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title mb-4 text-center">Editar menu</div>
      <v-form ref="form">
        <v-text-field
          class="mb-3"
          @keydown.enter="(e) => edit(e.target.value)"
          :value="menu.title"
          label="Título"
          name="title"
          outlined="outlined"
          dense="dense"
          hide-details="hide-details"
        ></v-text-field>
        <div class="grey--text text--lighten-1 font-weight-bold text-caption">
          Submenus
        </div>
        <v-card class="mb-4" outlined="outlined">
          <v-list class="py-0">
            <template v-for="submenu of menu.submenus">
              <v-list-item class="submenuitem" :key="submenu.id">
                <v-list-item-title
                  class="text-body-2 submenuitem-title"
                  @keydown.enter="
                    (e) => editSubmenu(submenu, e.target.textContent)
                  "
                  contenteditable="contenteditable"
                  >{{ submenu.title }}</v-list-item-title
                >
                <v-list-item-action>
                  <v-btn
                    @click="removeSubmenu(submenu.id)"
                    icon="icon"
                    small="small"
                  >
                    <v-icon size="14">fas fa-trash</v-icon>
                  </v-btn>
                </v-list-item-action>
              </v-list-item>
              <v-divider :key="submenu.id"></v-divider>
            </template>
          </v-list>
          <div class="submenu-add-input py-1">
            <v-icon class="pa-3" small="small">fas fa-plus</v-icon>
            <input
              @keydown.enter="addSubmenu"
              @blur="(e) => (e.target.value = '')"
              placeholder="Adicionar Submenu"
            />
          </div>
        </v-card>
      </v-form>
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
    menu: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    loading: false,
  }),
  methods: {
    edit(title) {
      this.loading = true;
      this.$rest("modulesMenu")
        .put({
          id: this.menu.id,
          data: {
            title,
          },
        })
        .finally(() => {
          this.loading = false;
        });
    },
    addSubmenu(e) {
      this.loading = true;
      this.$rest("modulesSubmenu")
        .post({
          data: {
            title: e.target.value,
          },
          params: {
            menuId: this.menu.id,
          },
        })
        .then((submenu) => {
          this.menu.submenus.push(submenu);
        })
        .finally(() => {
          this.loading = false;
        });
    },
    editSubmenu(submenu, title) {
      this.loading = true;
      this.$rest("modulesSubmenu")
        .put({
          id: submenu.id,
          data: {
            title,
          },
        })
        .then(() => {
          submenu.title = title;
        })
        .finally(() => {
          this.loading = false;
        });
    },
    removeSubmenu(id) {
      this.loading = true;

      this.$rest("modulesSubmenu")
        .delete({
          id: id,
        })
        .finally(() => {
          const i = this.menu.submenus.findIndex(
            (submenu) => submenu.id === id
          );
          this.menu.submenus.splice(i, 1);

          this.loading = false;
        });
    },
  },
  components: {
    Loading,
  },
};
</script>

<style>
.submenuitem-title {
  height: 52px;
  display: flex;
  align-items: center;
}
.submenuitem-title:focus {
  outline: none;
}
.submenu-add-input {
  display: flex;
  background-color: #333;
}
.submenu-add-input input:focus {
  outline: none;
}
.submenu-add-input input {
  color: white;
}
</style>
