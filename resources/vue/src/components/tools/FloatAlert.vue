<template>
  <v-snackbar
    :value="value && visible"
    :color="color"
    top="top"
    :timeout="timeout"
  >
    <v-icon color="white" left="left" size="16">fas fa-info-circle</v-icon
    ><span> <slot></slot></span>
    <v-spacer></v-spacer>
  </v-snackbar>
</template>

<script>
export default {
  props: {
    timeout: {
      type: Number,
      default: 4000,
    },
    value: {
      default: false,
    },
    color: {
      type: String,
      default: "red",
    },
  },
  data: () => ({
    visible: null,
  }),
  watch: {
    value(v) {
      if (v) {
        this.visible = true;
        this.timetoutId = window.setTimeout(
          () => (this.visible = false),
          this.timeout
        );
      } else {
        window.clearInterval(this.timetoutId);
        this.visible = false;
      }

      this.$emit("input");
    },
  },
  mounted() {
    if (this.value) this.visible = true;
    else this.visible = false;
  },
};
</script>

<style></style>
