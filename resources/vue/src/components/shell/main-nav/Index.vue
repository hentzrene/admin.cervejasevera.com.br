<template>
  <div>
    <app-bar @toggledrawer="drawer = !drawer"></app-bar>
    <navigation-drawer v-model="drawer"></navigation-drawer>
  </div>
</template>

<script>
import AppBar from "./AppBar";
import NavigationDrawer from "./NavigationDrawer";

export default {
  data: () => ({
    drawer: false,
  }),
  components: {
    AppBar,
    NavigationDrawer,
  },
};
</script>
