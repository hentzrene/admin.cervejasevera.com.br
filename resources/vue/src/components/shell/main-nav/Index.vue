<template lang="pug">
div
  app-bar(@toggledrawer="drawer = !drawer")
  navigation-drawer(v-model="drawer")
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
