<template>
  <div>
    <div>
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
        @changefieldkey="updateListHeaders"
        :items="data.fields"
        ref="fieldList"
      ></field-list>
      <div class="pa-4 text-body-2 text-center font-weight-bold" v-else>
        Nenhum campo foi adicionado!
      </div>
    </div>
    <div class="mt-6 mb-4">
      <div class="d-flex align-center mb-2">
        <div class="white--text font-weight-bold text-body-1">
          Cabeçalhos da lista
        </div>
      </div>
      <v-chip-group v-model="listHeaders" column="column" multiple="multiple">
        <v-chip value="id" filter outlined>Id</v-chip>
        <v-chip
          v-for="({ name, key }, i) in data.fields"
          :value="key"
          :key="i"
          filter="filter"
          outlined="outlined"
          >{{ name }}</v-chip
        >
        <v-chip value="showFrom" filter outlined>Começo</v-chip>
        <v-chip value="showUp" filter outlined>Fim</v-chip>
        <v-btn
          @click="sendListHeaders"
          :loading="sendingListHeaders"
          color="secondary darken-1"
          fab="fab"
          small="small"
          depressed="depressed"
        >
          <v-icon>fas fa-save</v-icon>
        </v-btn>
      </v-chip-group>
      <div
        class="pt-8 text-body-2 text-center font-weight-bold red--text"
        v-if="noListHeaders"
      >
        Deve haver no mínimo um cabeçalho.
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
