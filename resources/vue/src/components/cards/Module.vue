<template>
  <v-card
    class="module-card"
    :to="to"
    height="100%"
    color="primary lighten-1"
    outlined="outlined"
  >
    <v-icon size="50" color="grey lighten-3">{{ icon }}</v-icon
    ><span
      class="font-weight-medium mt-2 grey--text text--lighten-5 text-center module-card-title"
      >{{ title }}</span
    >
  </v-card>
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
.module-card {
  display: grid !important;
  grid-template-rows: 60px max-content !important;
  justify-content: center !important;
  align-content: center !important;
}
.module-card-title {
  white-space: break-spaces !important;
}
</style>
