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
        @changefieldkey="updateListHeaders"
        :items="fields"
        ref="fieldList"
      ></field-list>
      <div class="pa-4 text-body-2 text-center font-weight-bold" v-else>
        Nenhum campo foi adicionado!
      </div>
    </div>
    <div class="mt-6">
      <div class="d-flex align-center mb-2">
        <div class="white--text font-weight-bold text-body-1">
          Cabeçalhos da lista
        </div>
      </div>
      <v-chip-group v-model="listHeaders" column="column" multiple="multiple">
        <v-chip value="id" filter outlined>Id</v-chip>
        <template v-for="({ name, key }, i) in fields">
          <v-chip v-if="name" :value="key" :key="i" filter outlined>{{
            name
          }}</v-chip>
        </template>
        <v-chip value="showFrom" filter outlined>Começo</v-chip>
        <v-chip value="showUp" filter outlined>Fim</v-chip>
      </v-chip-group>
      <div
        class="pt-8 text-body-2 text-center font-weight-bold red--text"
        v-if="noListHeaders"
      >
        Deve haver no mínimo um cabeçalho.
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
