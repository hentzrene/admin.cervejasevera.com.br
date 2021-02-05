<template lang="pug">
div
  div
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
      @changefieldkey="updateListHeaders",
      :items="data.fields",
      ref="fieldList"
    )
    .pa-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum campo foi adicionado!
  .mt-6.mb-4
    .d-flex.align-center.mb-2
      .white--text.font-weight-bold.text-body-1 Cabeçalhos da lista
    v-chip-group(v-model="listHeaders", column, multiple)
      v-chip(
        v-for="({ name, key }, i) in data.fields",
        :value="key",
        :key="i",
        filter,
        outlined
      ) {{ name }}
      v-btn(
        @click="sendListHeaders",
        :loading="sendingListHeaders",
        color="secondary darken-1",
        fab,
        small,
        depressed
      )
        v-icon fas fa-save
    .pt-8.text-body-2.text-center.font-weight-bold.red--text(
      v-if="noListHeaders"
    ) Deve haver no mínimo um cabeçalho.
    add-field(v-model="addDialog", :types="fieldsTypes")
</template>

<script>
import FieldItem from "./FieldItem";
import FieldList from "./FieldList";
import AddField from "./AddField";
import Tooltip from "@/components/tools/Tooltip";

export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    noListHeaders: null,
    sendingListHeaders: false,
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
    listHeaders: {
      get() {
        return this.data.viewOptions.listHeaders;
      },
      set(v) {
        this.data.viewOptions.listHeaders = v;
      },
    },
  },
  methods: {
    validate() {
      const fieldList = this.$refs.fieldList;

      if (!fieldList) return false;

      if (!this.listHeaders.length) this.noListHeaders = true;
      else this.noListHeaders = false;

      return fieldList.validate();
    },
    updateListHeaders() {
      for (let header of this.listHeaders) {
        if (!this.fields.find(({ key }) => key === header)) {
          this.listHeaders.splice(this.listHeaders.indexOf(header), 1);
        }
      }
    },
    sendListHeaders() {
      this.sendingListHeaders = true;
      this.$rest("modules")
        .put({
          id: this.moduleId,
          data: {
            value: { listHeaders: this.data.viewOptions.listHeaders },
          },
          prop: "viewOptions",
        })
        .finally(() => {
          this.sendingListHeaders = false;
        });
    },
  },
  watch: {
    listHeaders(v) {
      if (v.length) this.noListHeaders = false;
    },
  },
  components: {
    FieldItem,
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
