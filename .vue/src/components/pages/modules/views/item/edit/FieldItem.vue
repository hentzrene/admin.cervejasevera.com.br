<template lang="pug">
v-card.pa-3(color="primary", max-width="400")
  v-form.field-item(ref="form")
    .field-item-inputs.d-flex.align-center
      v-text-field(
        @keydown.enter="updateProperty($event.target.value, 'name')",
        @blur="updateProperty($event.target.value, 'name')",
        :value="data.name",
        :loading="updating.name",
        :disabled="updating.name || updating.typeId || removing",
        label="Nome",
        dense,
        solo,
        hide-details
      )
      v-select(
        @input="updateProperty($event, 'typeId')",
        :value="data.typeId",
        :items="types",
        :loading="updating.typeId",
        :disabled="updating.name || updating.typeId || removing",
        label="Tipo",
        dense,
        solo,
        hide-details
      )
      v-btn(
        @click="remove",
        :loading="removing",
        color="secondary darken-2",
        small,
        icon
      )
        v-icon(small) fas fa-trash
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import { required, lowerCase } from "@/components/forms/rules";

export default {
  props: {
    data: {
      type: Object,
      default: () => ({
        id: null,
        name: null,
        key: null,
        type: null,
        required: false,
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
    removing: false,
    updating: {
      name: false,
      typeId: false,
    },
  }),
  computed: {
    moduleId() {
      return this.$route.params.sub;
    },
  },
  methods: {
    validate() {
      return this.$refs.form.validate();
    },
    remove() {
      this.removing = true;
      this.$rest("modulesFields")
        .delete({ id: this.data.id, params: { moduleId: this.moduleId } })
        .then(() => this.$emit("remove"))
        .finally(() => (this.removing = false));
    },
    updateProperty(value, prop) {
      if (this.data[prop] === value) return false;

      this.updating[prop] = true;
      this.$rest("modulesFields")
        .put({
          id: this.data.id,
          data: { value: value },
          prop,
          params: { moduleId: this.moduleId },
        })
        .then(() => (this.data[prop] = value))
        .finally(() => (this.updating[prop] = false));
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
