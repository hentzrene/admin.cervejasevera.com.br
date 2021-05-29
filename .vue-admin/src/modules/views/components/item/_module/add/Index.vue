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
    field-list(v-if="fields.length", :items="fields", ref="fieldList")
    .pa-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum campo foi adicionado!
</template>

<script>
import FieldList from "@/modules/fields/_module/AddFieldList";
import Tooltip from "@/components/tools/Tooltip";

export default {
  data: () => ({
    fields: [],
    noListHeaders: null,
  }),
  computed: {
    data() {
      return {
        fields: this.fields,
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

      return fieldList.validate();
    },
  },
  beforeCreate() {
    this.$rest("modulesFieldsTypes").get();
  },
  components: {
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
