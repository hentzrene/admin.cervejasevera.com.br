<template lang="pug">
tooltip(:tip="text", top)
  v-btn.text-none(
    @click="(e) => $emit('click', e)",
    :disabled="disabled",
    color="secondary",
    depressed,
    x-small,
    fab
  )
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
