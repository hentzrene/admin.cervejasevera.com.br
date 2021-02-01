<template lang="pug">
v-card(outlined, dark)
  .pa-4.text-body-2.text-center.font-weight-bold O módulo "{{ data.name }}" apenas pode ser alterado no código fonte.
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

<style>
</style>
