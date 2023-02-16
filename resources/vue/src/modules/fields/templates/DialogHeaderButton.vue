<template>
  <tooltip :tip="text" top="top">
    <v-btn
      class="text-none"
      @click="(e) => $emit('click', e)"
      :disabled="disabled"
      color="secondary"
      depressed="depressed"
      x-small="x-small"
      fab="fab"
    >
      <v-icon small="small">{{ icon }}</v-icon>
    </v-btn>
  </tooltip>
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";

export default {
  props: {
    icon: {
      type: String,
      required: true,
    },
    text: {
      type: String,
      required: true,
    },
    disabled: Boolean,
  },
  components: {
    Tooltip,
  },
};
</script>

<style></style>
