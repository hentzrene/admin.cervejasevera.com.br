<template>
  <div>
    <div>
      <div class="d-flex align-center mb-2">
        <div class="white--text font-weight-bold text-body-1">Campos</div>
        <tooltip tip="Adicionar" top="top">
          <v-btn
            class="ml-2"
            @click="addField"
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
        v-if="fields.length"
        :items="fields"
        ref="fieldList"
        :unique-option="false"
      ></field-list>
      <div class="pa-4 text-body-2 text-center font-weight-bold" v-else>
        Nenhum campo foi adicionado!
      </div>
    </div>
  </div>
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
