<template lang="pug">
v-card.pa-3(color="primary")
  v-form.field-item(ref="form")
    .field-item-inputs
      v-text-field(
        v-model="data.name",
        :rules="[rules.required]",
        label="Nome",
        dense,
        solo
      )
      v-text-field(
        v-model="data.key",
        @input="$emit('changekey')",
        :rules="[rules.required, rules.alphaNumUnderline]",
        label="Chave",
        dense,
        solo
      )
      v-select(
        v-model="data.type",
        :items="types",
        :rules="[rules.required]",
        label="Tipo",
        dense,
        solo
      )
    .field-item-switches.d-flex
      tooltip(v-if="uniqueOption", tip="Único", top)
        v-btn(@click="data.unique = !data.unique", icon)
          v-icon(color="cyan", :disabled="!data.unique") fas fa-fingerprint
    .field-item-btns.d-flex.align-center.justify-end
      v-btn(@click="$emit('remove')", color="secondary darken-2", small)
        v-icon(left) fas fa-trash
        span.text-none Remover
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import { required, alphaNumUnderline } from "@/components/forms/rules";

export default {
  props: {
    data: {
      type: Object,
      default: () => ({
        name: null,
        key: null,
        type: null,
        unique: false,
      }),
    },
    types: {
      type: Array,
      required: true,
    },
    uniqueOption: {
      type: Boolean,
      default: true,
    },
  },
  data: () => ({
    rules: {
      required,
      alphaNumUnderline,
    },
  }),
  computed: {},
  methods: {
    validate() {
      return this.$refs.form.validate();
    },
  },
  components: {
    Tooltip,
  },
};
</script>

<style>
.field-item {
  display: grid;
  grid-template: "inputs inputs" "switches btns";
  column-gap: 16px;
}
.field-item-inputs {
  grid-area: inputs;
  display: grid;
  grid-template-columns: repeat(3, calc(33% - (16px / 3)));
  gap: 8px;
}
.field-item-switches {
  grid-area: switches;
  gap: 4px;
}
.field-item-btns {
  grid-area: btns;
  gap: 16px;
}
</style>
