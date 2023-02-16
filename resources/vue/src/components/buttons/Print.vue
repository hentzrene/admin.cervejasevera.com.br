<template>
  <toolbar-button
    @click="print"
    tip="Imprimir"
    icon="fas fa-print"
    dark="dark"
  ></toolbar-button>
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
