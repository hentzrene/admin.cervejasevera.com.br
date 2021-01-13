<template lang="pug">
toolbar-button(@click="print", title="Imprimir", icon="fas fa-print", dark)
</template>

<script>
import ToolbarButton from "./Toolbar";

export default {
  components: {
    ToolbarButton,
  },
  methods: {
    print() {
      window.print();
    },
  },
};
</script>
