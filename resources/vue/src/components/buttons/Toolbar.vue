<template>
  <div>
    <tooltip :tip="tip" top="top">
      <v-btn
        v-if="sm"
        v-bind="$attrs"
        v-on="$listeners"
        icon="icon"
        exact="exact"
        small="small"
      >
        <v-icon small="small">{{ icon }}</v-icon>
      </v-btn>
      <v-btn
        class="toolbar-button-desktop"
        v-else
        v-bind="$attrs"
        v-on="$listeners"
        text="text"
        exact="exact"
        small="small"
      >
        <v-icon small="small">{{ icon }}</v-icon>
      </v-btn>
    </tooltip>
  </div>
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";

export default {
  props: {
    icon: {
      type: String,
      required: true,
    },
    tip: {
      type: String,
      default: "",
    },
  },
  computed: {
    sm() {
      return this.$vuetify.breakpoint.smAndDown;
    },
  },
  components: {
    Tooltip,
  },
};
</script>

<style>
.toolbar-button-desktop {
  min-width: 20px !important;
}
</style>
