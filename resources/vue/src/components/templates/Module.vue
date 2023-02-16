<template>
  <v-sheet
    class="mx-auto module-page pa-2"
    :width="width"
    :max-width="maxWidth"
    color="transparent"
  >
    <v-toolbar
      class="elevation-0 rounded-t"
      color="accent darken-1"
      width="100%"
      dark="dark"
      dense="dense"
    >
      <h4>{{ title }}</h4>
      <v-spacer></v-spacer>
      <div class="module-page-toolbar">
        <slot name="toolbar"></slot>
        <toolbar-button
          v-if="prev && !sm"
          :to="prev"
          tip="Voltar"
          icon="fas fa-undo-alt"
          dark="dark"
        ></toolbar-button>
      </div>
    </v-toolbar>
    <slot></slot>
  </v-sheet>
</template>

<script>
import ToolbarButton from "@/components/buttons/Toolbar";

export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    width: {
      type: String,
      default: "100%",
    },
    maxWidth: {
      type: String,
      default: "100%",
    },
  },
  computed: {
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
    prev() {
      const path = this.$route.path;

      return /^\/[^/]+\/.+/.test(path) && path.replace(/(\/[^/]+)(.*)$/, "$1");
    },
  },
  beforeCreate() {
    this.$rest("modules").get({ keepCache: true });
  },
  components: {
    ToolbarButton,
  },
};
</script>

<style>
.module-page .v-data-footer {
  height: 58px !important;
}

.module-page .v-data-footer__select {
  display: none !important;
}
.module-page-toolbar {
  display: flex;
  gap: 8px;
}
@media print {
  .v-application--wrap > :not(.v-main),
  .module-page-toolbar,
  .v-data-table .v-data-footer,
  .v-data-table tr > :last-child,
  .v-data-table tr > :first-child,
  .module-search {
    display: none !important;
  }
  .v-data-table__wrapper,
  .v-main__wrap {
    height: auto !important;
  }
  .v-main {
    padding: 0 !important;
  }
  body,
  .v-main__wrap {
    overflow-y: scroll !important;
  }
  * {
    color: black !important;
  }
}
</style>
