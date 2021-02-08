<template lang="pug">
v-btn.text-none(
  @click="(e) => $emit('click', e)",
  :disabled="disabled",
  color="secondary",
  depressed,
  small
)
  v-icon(left, small) {{ icon }}
  span
    slot
</template>

<script>
export default {
  props: {
    icon: {
      type: String,
      required: true,
    },
    disabled: Boolean,
  },
};
</script>

<style>
</style>
