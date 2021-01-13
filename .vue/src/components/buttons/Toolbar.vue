<template lang="pug">
div
  tooltip(v-if="sm", :tip="title", top)
    v-btn(v-bind="$attrs", v-on="$listeners", icon, exact, small)
      v-icon(small) {{ icon }}
  v-btn(v-else, v-bind="$attrs", v-on="$listeners", text, exact, small)
    v-icon(small, left) {{ icon }}
    span {{ title }}
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";

export default {
  props: {
    icon: {
      type: String,
      required: true,
    },
    title: {
      type: String,
      required: true,
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
