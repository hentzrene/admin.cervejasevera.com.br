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
  v-list-item-group.mt-2(v-model="current")
    v-list(dense, nav)
      template(v-for="({ text, to, icon, items }, i) in nav")
        v-list-item(v-if="!items", :key="i", :to="to", link)
          v-list-item-icon
            v-icon {{ icon }}
          v-list-item-content
            v-list-item-title.body-2 {{ text }}
        v-list-group(
          v-else,
          :key="i",
          :prepend-icon="icon",
          no-action,
          color="black"
        )
          template(v-slot:activator)
            v-list-item.pa-0
              v-list-item-title.body-2 {{ text }}
          v-list-item(
            v-for="({ to, text }, i) in items",
            :key="i",
            :to="to",
            link
          )
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
      return this.modules.map(({ name, key, icon }) => ({
        text: name,
        to: "/admin/" + key,
        icon,
      }));
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
</style>
