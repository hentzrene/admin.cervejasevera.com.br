<template lang="pug">
div
  tooltip(:tip="tip", top)
    v-btn(v-if="sm", v-bind="$attrs", v-on="$listeners", icon, exact, small)
      v-icon(small) {{ icon }}
    v-btn.toolbar-button-desktop(v-else, v-bind="$attrs", v-on="$listeners", text, exact, small)
      v-icon(small) {{ icon }}
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
