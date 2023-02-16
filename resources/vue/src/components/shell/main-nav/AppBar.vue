<template>
  <v-app-bar color="primary lighten-2" fixed="fixed" app="app" dense="dense">
    <router-link class="h-100 white--text" v-if="sm" to="/"
      ><img
        class="py-2"
        :src="`${prefixPath}/img/logo.svg`"
        height="100%"
        contain="contain"
    /></router-link>
    <div class="grey--text text--lighten-3 text-center text-caption ml-3">
      MRX CMS Headless v{{ version }}
    </div>
    <v-spacer></v-spacer>
    <v-menu
      v-model="menu"
      :close-on-content-click="false"
      min-width="200px"
      rounded="rounded"
      offset-y="offset-y"
      bottom="bottom"
    >
      <template v-slot:activator="{ on }">
        <v-btn icon="icon" x-large="x-large" v-on="on">
          <v-avatar color="accent" size="42"
            ><span class="white--text">{{ userInitial }}</span></v-avatar
          >
        </v-btn>
      </template>
      <v-card>
        <v-list-item-content
          class="justify-center pb-0 primary lighten-2 white--text"
        >
          <div class="mx-auto text-center">
            <v-avatar class="mb-2" color="accent"
              ><span class="white--text">{{ userInitial }}</span></v-avatar
            >
            <h4>{{ user.name }}</h4>
            <p class="caption mt-1">{{ user.email }}</p>
            <v-divider></v-divider>
            <template v-if="user.type == 1">
              <v-btn
                class="text-none"
                to="/accounts"
                color="white"
                depressed="depressed"
                text="text"
                block="block"
                tile="tile"
              >
                <v-icon left="left" small="small">fas fa-users</v-icon
                ><span>Contas</span>
              </v-btn>
              <v-divider></v-divider>
              <v-btn
                class="text-none"
                to="/modules"
                color="white"
                depressed="depressed"
                text="text"
                block="block"
                tile="tile"
              >
                <v-icon left="left" small="small">fas fa-cogs</v-icon
                ><span>Módulos</span>
              </v-btn>
              <v-divider></v-divider>
              <v-btn
                class="text-none"
                to="/email"
                color="white"
                depressed="depressed"
                text="text"
                block="block"
                tile="tile"
              >
                <v-icon left="left" small="small">fas fa-envelope</v-icon
                ><span>E-mail</span>
              </v-btn>
              <v-divider></v-divider>
              <v-btn
                class="text-none"
                to="/tags"
                color="white"
                depressed="depressed"
                text="text"
                block="block"
                tile="tile"
              >
                <v-icon left="left" small="small">fas fa-code</v-icon
                ><span>Tags</span>
              </v-btn>
              <v-divider></v-divider>
            </template>
            <v-btn
              class="text-none"
              @click="logout"
              :loading="loading"
              color="white"
              depressed="depressed"
              text="text"
              block="block"
              tile="tile"
            >
              <v-icon left="left" small="small">fas fa-sign-out-alt</v-icon
              ><span>Sair</span>
            </v-btn>
          </div>
        </v-list-item-content>
      </v-card>
    </v-menu>
    <v-app-bar-nav-icon
      v-if="sm"
      @click="() => $emit('toggledrawer')"
      color="grey lighten-2"
    ></v-app-bar-nav-icon>
  </v-app-bar>
</template>

<script>
export default {
  data: () => ({
    loading: false,
    menu: false,
  }),
  computed: {
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
    user() {
      return this.$store.state.user;
    },
    userInitial() {
      return this.user.name.slice(0, 1);
    },
  },
  watch: {
    $route() {
      this.menu = false;
    },
  },
  methods: {
    logout() {
      this.loading = true;
      this.$auth
        .logout()
        .then(() => this.$router.push("/entrar"))
        .finally(() => (this.loading = false));
    },
  },
};
</script>
