<template>
  <v-navigation-drawer
    @input="() => $emit('input')"
    :value="value"
    :temporary="sm"
    :permanent="!sm"
    color="primary lighten-2"
    width="240px"
    fixed="fixed"
    app="app"
    dark="dark"
  >
    <div class="pa-4 align-content-center">
      <router-link class="white--text h-100" to="/"
        ><img
          class="logo"
          :src="`${prefixPath}/img/logo.svg`"
          contain="contain"
      /></router-link>
    </div>
    <v-divider></v-divider>
    <div
      class="pa-2 mx-2 mt-10 white--text font-weight-bold primary rounded-pill text-body-2 text-center"
      v-if="!modules.length"
    >
      Nenhum módulo adicionado.
    </div>
    <template v-else>
      <v-list-item-group class="mt-2" v-model="current">
        <v-list dense="dense" nav="nav"
          ><template v-for="(submenus, menuTitle) in nav"
            ><template v-if="menuTitle === '$'">
              <v-list-item
                v-for="({ to, icon, text }, i) in nav.$.$"
                :key="i"
                :to="to"
                link="link"
              >
                <v-list-item-icon class="mr-3">
                  <v-icon size="20">{{ icon }}</v-icon>
                </v-list-item-icon>
                <v-list-item-content>
                  <v-list-item-title class="body-2">{{
                    text
                  }}</v-list-item-title>
                </v-list-item-content>
              </v-list-item>
            </template>
            <v-list-group
              v-else
              :key="menuTitle"
              no-action="no-action"
              color="white"
              ><template #activator>
                <v-list-item-title
                  class="body-2 text-uppercase font-weight-bold"
                  >{{ menuTitle }}</v-list-item-title
                > </template
              ><template v-for="(items, submenuTitle) in submenus"
                ><template v-if="submenuTitle === '$'">
                  <v-list-item
                    class="pl-4"
                    v-for="({ to, icon, text }, i) in submenus.$"
                    :key="i"
                    :to="to"
                    link="link"
                  >
                    <v-list-item-icon class="mr-3">
                      <v-icon size="20">{{ icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title class="body-2">{{
                        text
                      }}</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </template>
                <v-list-group
                  v-else
                  :key="submenuTitle"
                  no-action="no-action"
                  sub-group="sub-group"
                  color="white"
                  ><template #activator>
                    <v-list-item-title
                      class="body-2 text-uppercase font-weight-bold pl-0"
                      >{{ submenuTitle }}</v-list-item-title
                    >
                  </template>
                  <v-list-item
                    class="pl-14"
                    v-for="({ to, icon, text }, i) in submenus[submenuTitle]"
                    :key="i"
                    :to="to"
                    link="link"
                  >
                    <v-list-item-icon class="mr-3">
                      <v-icon size="20">{{ icon }}</v-icon>
                    </v-list-item-icon>
                    <v-list-item-content>
                      <v-list-item-title class="body-2">{{
                        text
                      }}</v-list-item-title>
                    </v-list-item-content>
                  </v-list-item>
                </v-list-group>
              </template></v-list-group
            >
          </template></v-list
        >
      </v-list-item-group>
    </template>
  </v-navigation-drawer>
</template>

<script>
import { groupBy } from "../../utils";

export default {
  props: {
    value: Boolean,
  },
  data() {
    return {
      current: this.$route.path,
    };
  },
  computed: {
    nav() {
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
            ({ name, key, icon }) => ({
              text: name,
              to: "/" + key,
              icon,
            })
          );
        }
      }

      return menu;
    },
    modules() {
      return this.$rest("modules").list;
    },
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
};
</script>

<style>
.v-navigation-drawer {
  z-index: 99;
}

.v-navigation-drawer .logo {
  object-fit: contain;
  max-height: 50px;
}
.v-navigation-drawer__content {
  overflow: hidden !important;
}
.v-navigation-drawer__content .v-item-group {
  height: calc(100% - 100px) !important;
  overflow-y: scroll;
}

.v-navigation-drawer__content
  .v-list-group__header__append-icon
  .fa-chevron-down {
  font-size: 14px !important;
}

.v-navigation-drawer__content .v-list-group--sub-group .v-list-group__header {
  padding-left: 19px !important;
}
</style>
