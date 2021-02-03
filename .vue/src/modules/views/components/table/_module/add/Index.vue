<template lang="pug">
div
  div
    .d-flex.align-center.mb-2
      .white--text.font-weight-bold.text-body-1 Campos
      tooltip(tip="Adicionar", top)
        v-btn.ml-2(
          @click="addField",
          color="secondary",
          fab,
          depressed,
          x-small
        )
          v-icon fas fa-plus
    field-list(
      v-if="fields.length",
      @changefieldkey="updateListHeaders",
      :items="fields",
      ref="fieldList"
    )
    .pa-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum campo foi adicionado!
  .mt-6
    .d-flex.align-center.mb-2
      .white--text.font-weight-bold.text-body-1 Cabeçalhos da lista
    v-chip-group(v-model="listHeaders", column, multiple)
      template(v-for="({ name, key }, i) in fields")
        v-chip(v-if="name", :value="key", :key="i", filter, outlined) {{ name }}
    .pt-8.text-body-2.text-center.font-weight-bold.red--text(
      v-if="noListHeaders"
    ) Deve haver no mínimo um cabeçalho.
</template>

<script>
import FieldItem from "./FieldItem";
import FieldList from "./FieldList";
import Tooltip from "@/components/tools/Tooltip";

export default {
  data: () => ({
    fields: [],
    listHeaders: [],
    noListHeaders: null,
  }),
  computed: {
    data() {
      return {
        fields: this.fields,
        viewOptions: { listHeaders: this.listHeaders },
      };
    },
    fieldsTypes() {
      return this.$rest("modulesFieldsTypes").list.map(({ id, name }) => ({
        text: name,
        value: id,
      }));
    },
  },
  methods: {
    addField() {
      this.fields.push({
        name: null,
        key: null,
        type: null,
        unique: false,
        private: false,
      });
    },
    validate() {
      const fieldList = this.$refs.fieldList;

      if (!fieldList) return false;

      if (!this.listHeaders.length) {
        this.noListHeaders = true;
        return false;
      } else {
        this.noListHeaders = false;
      }

      return fieldList.validate();
    },
    updateListHeaders() {
      for (let header of this.listHeaders) {
        if (!this.fields.find(({ key }) => key === header)) {
          this.listHeaders.splice(this.listHeaders.indexOf(header), 1);
        }
      }
    },
  },
  watch: {
    listHeaders(v) {
      if (v.length) this.noListHeaders = false;
    },
  },
  beforeCreate() {
    this.$rest("modulesFieldsTypes").get();
  },
  components: {
    FieldItem,
    FieldList,
    Tooltip,
  },
};
</script>

<style>
.field-list {
  gap: 20px;
}
.v-chip .v-icon {
  font-size: 14px;
}
</style>
