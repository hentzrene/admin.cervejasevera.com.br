<template lang="pug">
grid-item(col-end=2, col-end-sm=1).d-flex
  v-menu(offset-y :close-on-content-click="false")
    template(#activator="{ on, attrs }")
      v-btn(v-bind="attrs" v-on="on" :color="value_" fab depressed x-small).mt-1.mr-2
        v-icon fas fa-tint
    v-color-picker(v-model="value_" mode="hexa" dark)
  v-text-field(
    :label="label",
    :value="value_",
    :name="name",
    readonly
    dense,
    outlined,
    dark
  )
</template>

<script>
import mixin from "../../mixin";

export default {
  data: () => ({
    picker: false,
    value_: null,
  }),
  mounted() {
    this.value_ = this.value;
  },
  watch: {
    value(v) {
      this.value_ = v;
    },
  },
  mixins: [mixin],
};
</script>
