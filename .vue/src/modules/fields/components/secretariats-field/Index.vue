<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  v-select(
    :label="label",
    :value="value",
    :name="name",
    :items="secretariats",
    :loading="loading",
    dense,
    outlined,
    dark
  )
</template>

<script>
import mixin from "../../mixin";

export default {
  data: () => ({
    loading: false,
  }),
  computed: {
    secretariats() {
      return this.$rest("secretariats").list.map(({ name, id }) => ({
        text: name,
        value: id,
      }));
    },
  },
  created() {
    this.loading = true;
    this.$rest("secretariats")
      .get({
        params: {
          fields: "id,name",
        },
      })
      .finally(() => (this.loading = false));
  },
  mixins: [mixin],
};
</script>
