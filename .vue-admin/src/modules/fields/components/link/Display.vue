<template>
  <div>
    <span>{{ value }}</span>
    <v-btn @click.stop="copy" class="ml-1" icon small>
      <v-icon small>fas fa-copy</v-icon>
    </v-btn>
  </div>
</template>

<script>
export default {
  props: {
    value: {
      type: String,
    },
  },
  methods: {
    copy() {
      navigator.clipboard.writeText(this.value || "");
    },
  },
};
</script>
