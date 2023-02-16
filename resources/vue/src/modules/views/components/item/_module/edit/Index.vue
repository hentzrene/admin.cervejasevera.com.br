<template>
  <div>
    <div class="mb-4">
      <div class="d-flex align-center mb-2">
        <div class="white--text font-weight-bold text-body-1">Campos</div>
        <tooltip tip="Adicionar" top="top">
          <v-btn
            class="ml-2"
            @click="addDialog = true"
            color="secondary"
            fab="fab"
            depressed="depressed"
            x-small="x-small"
          >
            <v-icon>fas fa-plus</v-icon>
          </v-btn>
        </tooltip>
      </div>
      <field-list
        v-if="data.fields && data.fields.length"
        :items="data.fields"
        ref="fieldList"
      ></field-list>
      <div class="pa-4 text-body-2 text-center font-weight-bold" v-else>
        Nenhum campo foi adicionado!
      </div>
      <add-field v-model="addDialog" :types="fieldsTypes"></add-field>
    </div>
  </div>
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
