<template>
  <div class="pa-4 text-body-2 text-center font-weight-bold">
    O módulo "{{ data.name }}" apenas pode ser alterado no código fonte.
  </div>
</template>

<script>
export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
};
</script>

<style></style>
