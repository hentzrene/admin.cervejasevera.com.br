<template lang="pug">
grid-item(col-end=2, col-end-sm=1)
  v-text-field(
    :label="label",
    :value="value",
    :name="name",
    :rules="[rules.url]",
    append-icon="fas fa-globe",
    type="url",
    dense,
    outlined,
    dark
  )
</template>

<script>
import mixin from "../../mixin";
import { url } from "@/components/forms/rules";

export default {
  data: () => ({
    rules: {
      url,
    },
  }),
  mixins: [mixin],
};
</script>
