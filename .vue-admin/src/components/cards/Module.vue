<template lang="pug">
v-card.d-flex.align-center.flex-column.justify-center(
  :to="to",
  height="100%",
  color="primary lighten-1",
  outlined
)
  v-icon(size="78", color="grey lighten-3") {{ icon }}
  span.font-weight-bold.mt-2.grey--text.text--lighten-5 {{ title }}
</template>

<script>
export default {
  props: {
    title: {
      type: String,
      required: true,
    },
    to: {
      type: String,
      required: true,
    },
    icon: {
      type: String,
      required: true,
    },
  },
};
</script>

<style>
</style>
