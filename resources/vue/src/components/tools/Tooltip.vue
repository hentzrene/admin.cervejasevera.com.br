<template lang="pug">
v-tooltip(:top="top", :right="right", :left="left", :bottom="bottom")
  template(#activator="{ on, attrs }")
    div(v-bind="attrs", v-on="on")
      slot
  span {{ tip }}
</template>

<script>
export default {
  props: {
    tip: {
      type: String,
      default: "",
    },
    top: {
      type: Boolean,
      default: false,
    },
    right: {
      type: Boolean,
      default: false,
    },
    bottom: {
      type: Boolean,
      default: false,
    },
    left: {
      type: Boolean,
      default: false,
    },
  },
};
</script>
