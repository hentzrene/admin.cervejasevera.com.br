<template lang="pug">
v-navigation-drawer(
  @input="() => $emit('input')",
  :value="value",
  :temporary="sm",
  :permanent="!sm",
  color="primary lighten-2",
  width="240px",
  fixed,
  app,
  dark
)
  .pa-4.align-content-center
    router-link.white--text.h-100(to="/")
      img.logo(src="/admin/img/logo.svg", contain)
  v-divider
  .pa-2.mx-2.mt-10.white--text.font-weight-bold.primary.rounded-pill.text-body-2.text-center(
    v-if="!modules.length"
  ) Nenhum módulo adicionado.
  template(v-else)
    v-list-item-group.mt-2(v-model="current")
      v-list(dense, nav)
        template(v-for="({ text, to, icon, items }, i) in nav")
          v-list-item(v-if="!items", :key="i", :to="to", link)
            v-list-item-icon
              v-icon {{ icon }}
            v-list-item-content
              v-list-item-title.body-2 {{ text }}
          v-list-group(v-else, :key="i", no-action, color="white")
            template(#activator)
              v-list-item-title.body-2.text-uppercase.font-weight-bold {{ text }}
            v-list-item.pl-8(
              v-for="({ text, to, icon }, i) in items",
              :key="i",
              :to="to",
              link
            )
              v-list-item-icon
                v-icon {{ icon }}
              v-list-item-content
                v-list-item-title.body-2 {{ text }}
</template>

<script>
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
      let withMenu = [];
      let withoutMenu = [];

      for (let m of this.modules)
        if (m.menuId) withMenu.push(m);
        else withoutMenu.push(m);

      withMenu = withMenu.reduce((r, v) => {
        const menu = r.find(({ text }) => text === v.menuTitle);

        if (!menu) {
          r.push({
            text: v.menuTitle,
            items: [{ text: v.name, to: "/" + v.key, icon: v.icon }],
          });
        } else {
          menu.items.push({ text: v.name, to: "/" + v.key, icon: v.icon });
        }

        return r;
      }, []);

      withoutMenu = withoutMenu.map(({ name, key, icon }) => ({
        text: name,
        to: "/" + key,
        icon,
      }));

      return [...withoutMenu, ...withMenu];
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
</style>
