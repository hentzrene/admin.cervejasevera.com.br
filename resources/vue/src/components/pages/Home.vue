<template>
  <v-sheet
    class="mx-auto pa-4 d-flex align-center"
    color="transparent"
    max-width="960px"
    min-height="100%"
  >
    <loading class="mx-auto" v-if="loading"></loading>
    <div
      class="pa-4 white--text font-weight-bold primary rounded-pill text-body-1 mx-auto"
      v-else-if="!modules.length"
    >
      Nenhum módulo adicionado.
    </div>
    <div class="mx-auto" v-else>
      <grid-container
        class="py-4"
        cols="125px 125px"
        cols-sm="repeat(4, 125px)"
        cols-lg="repeat(5, 125px)"
        gap="16px"
        ><template v-for="(submenus, menuTitle) in nav"
          ><template v-if="menuTitle === '$'">
            <v-responsive
              v-for="({ to, icon, text }, i) in nav.$.$"
              :aspect-ratio="1"
              :key="i"
              width="125px"
            >
              <module-card :to="to" :title="text" :icon="icon"></module-card>
            </v-responsive>
          </template>
          <grid-item
            v-else
            col-end="span 2"
            col-end-sm="span 4"
            col-end-lg="span 5"
            :key="menuTitle"
          >
            <div
              class="white--text text-body-1 text-uppercase font-weight-bold"
            >
              {{ menuTitle }}
            </div>
            <grid-container
              cols="100px 100px 100px"
              cols-sm="repeat(5, 120px)"
              cols-lg="repeat(6, 125px)"
              gap="16px"
              ><template v-for="(items, submenuTitle) in submenus">
                <v-responsive
                  v-for="({ to, icon, text }, i) in items"
                  :aspect-ratio="1"
                  :key="submenuTitle + i"
                  width="125"
                >
                  <module-card
                    :to="to"
                    :title="
                      submenuTitle !== '$' ? `${submenuTitle}${text}` : text
                    "
                    :icon="icon"
                  ></module-card>
                </v-responsive> </template
            ></grid-container>
          </grid-item> </template
      ></grid-container>
    </div>
  </v-sheet>
</template>

<script>
import Loading from "@/components/tools/Loading";
import ModuleCard from "@/components/cards/Module";
import { groupBy } from "../utils";

export default {
  data: () => ({ loading: true }),
  computed: {
    modules() {
      return this.$rest("modules").list;
    },
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
  },
  beforeCreate() {
    this.$rest("modules")
      .get()
      .finally(() => (this.loading = false));
  },
  components: {
    Loading,
    ModuleCard,
  },
};
</script>

<style></style>
