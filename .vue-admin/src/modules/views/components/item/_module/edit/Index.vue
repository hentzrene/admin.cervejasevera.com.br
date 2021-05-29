<template lang="pug">
div
  .mb-4
    .d-flex.align-center.mb-2
      .white--text.font-weight-bold.text-body-1 Campos
      tooltip(tip="Adicionar", top)
        v-btn.ml-2(
          @click="addDialog = true",
          color="secondary",
          fab,
          depressed,
          x-small
        )
          v-icon fas fa-plus
    field-list(
      v-if="data.fields && data.fields.length",
      :items="data.fields",
      ref="fieldList"
    )
    .pa-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum campo foi adicionado!
    add-field(v-model="addDialog", :types="fieldsTypes")
</template>

<script>
import FieldList from "@/modules/fields/_module/EditFieldList";
import AddField from "@/modules/fields/_module/AddField";
import Tooltip from "@/components/tools/Tooltip";

export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    addDialog: false,
  }),
  computed: {
    moduleId() {
      return this.$route.params.module;
    },
    fieldsTypes() {
      return this.$rest("modulesFieldsTypes").list.map(({ id, name }) => ({
        text: name,
        value: id,
      }));
    },
  },
  methods: {
    validate() {
      const fieldList = this.$refs.fieldList;

      if (!fieldList) return false;

      return fieldList.validate();
    },
  },
  components: {
    FieldList,
    AddField,
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
