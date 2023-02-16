<template>
  <v-tooltip :top="top" :right="right" :left="left" :bottom="bottom">
    <template #activator="{ on, attrs }">
      <div v-bind="attrs" v-on="on">
        <slot></slot>
      </div> </template
    ><span>{{ tip }}</span>
  </v-tooltip>
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
