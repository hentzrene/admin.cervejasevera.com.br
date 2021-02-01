<template lang="pug">
v-card.pa-3(color="primary", max-width="400")
  v-form.field-item(ref="form")
    .field-item-inputs.d-flex
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
        :rules="[rules.required, rules.lowerCase]",
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
      tooltip(tip="Único", top)
        v-btn(@click="data.unique = !data.unique", icon)
          v-icon(color="cyan", :disabled="!data.unique") fas fa-fingerprint
      tooltip(tip="Privado", top)
        v-btn(@click="data.private = !data.private", icon)
          v-icon(color="green", :disabled="!data.private") fas fa-shield-alt
    .field-item-btns.d-flex.align-center.justify-end
      v-btn(@click="$emit('remove')", color="secondary darken-2", small)
        v-icon(left) fas fa-trash
        span.text-none Remover
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import { required, lowerCase } from "@/components/forms/rules";

export default {
  props: {
    data: {
      type: Object,
      default: () => ({
        name: null,
        key: null,
        type: null,
        unique: false,
        private: false,
      }),
    },
    types: {
      type: Array,
      required: true,
    },
  },
  data: () => ({
    rules: {
      required,
      lowerCase,
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
