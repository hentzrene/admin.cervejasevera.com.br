import { groupBy } from "../../../../components/utils"; import { groupBy } from
"../../../../components/utils";
<template>
  <module-template
    :title="`${data.name} / Alterar`"
    width="800px"
    max-width="100%"
  >
    <template #toolbar>
      <toolbar-button
        @click="rangeDateActiveDialog = true"
        tip="Começo/Fim"
        icon="fas fa-calendar-alt"
      ></toolbar-button>
      <v-dialog v-model="rangeDateActiveDialog" max-width="330px">
        <v-card class="pa-4" dark="dark">
          <div class="title mb-4 text-center">Alterar período ativo</div>
          <v-form
            class="table-module-edit-rangedateactived"
            ref="rangeDateActiveForm"
          >
            <v-text-field
              class="mb-3"
              label="Começo"
              type="datetime-local"
              name="showFrom"
              :value="item.showFrom && item.showFrom.replace(' ', 'T')"
              :clearable="true"
              clear-icon="fas fa-times"
              outlined="outlined"
              dense="dense"
              hide-details="hide-details"
            ></v-text-field>
            <v-text-field
              class="mb-3"
              label="Fim"
              type="datetime-local"
              name="showUp"
              :value="item.showUp && item.showUp.replace(' ', 'T')"
              :clearable="true"
              clear-icon="fas fa-times"
              outlined="outlined"
              dense="dense"
              hide-details="hide-details"
            ></v-text-field>
          </v-form>
          <v-btn
            class="text-none"
            @click="setRangeDateActived"
            color="secondary"
            :loading="updatingRangeDateActived"
            :disabled="updatingRangeDateActived"
            block="block"
            depressed="depressed"
            >Alterar</v-btn
          >
        </v-card>
      </v-dialog>
    </template>
    <v-card class="pa-2 rounded-t-0" outlined="outlined" dark="dark">
      <v-form ref="form">
        <div
          class="mb-6"
          v-for="(fields, section) in sectionedFields"
          :key="section"
        >
          <template v-if="section !== 'null'">
            <div class="text-body-1 text-uppercase font-weight-bold">
              {{ section }}
            </div>
            <div
              class="table-module-edit-form mt-2 primary lighten-2 pa-2 rounded"
            >
              <component
                v-for="({ id, name, key, typeKey, options }, i) in fields"
                :value="item[key] || ''"
                :is="typeKey.toLowerCase() + 'field'"
                :label="name"
                :name="key"
                :key="i"
                :field-id="parseInt(id)"
                :field-options="options"
              ></component>
            </div>
          </template>
          <div class="table-module-edit-form mt-2 rounded" v-else>
            <component
              v-for="({ id, name, key, typeKey, options }, i) in fields"
              :value="item[key] || ''"
              :is="typeKey.toLowerCase() + 'field'"
              :label="name"
              :name="key"
              :key="i"
              :field-id="parseInt(id)"
              :field-options="options"
            ></component>
          </div>
        </div>
        <div class="d-flex justify-end">
          <v-btn
            class="text-none"
            @click="send"
            color="secondary"
            depressed="depressed"
            >{{ isPublished ? "Alterar" : "Publicar" }}</v-btn
          >
        </div>
      </v-form>
    </v-card>
  </module-template>
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import ToolbarButton from "@/components/buttons/Toolbar";
import fieldsElements from "../../../fields";
import { groupBy } from "../../../../components/utils";

export default {
  name: "TableAdd",
  props: {
    data: Object,
  },
  data: () => ({
    rangeDateActiveDialog: false,
    updatingRangeDateActived: false,
  }),
  computed: {
    itemId() {
      return this.$route.params.sub;
    },
    item() {
      return this.$rest(this.data.key).item;
    },
    isPublished() {
      return this.item.alteredAt && parseInt(this.item.active);
    },
    sectionedFields() {
      return groupBy(this.data.fields, "modules_sections_fields_title");
    },
  },
  methods: {
    send() {
      const form = this.$refs.form;

      if (form.validate()) {
        const data = Object.fromEntries(new FormData(form.$el).entries());

        for (let key in data) {
          if (typeof data[key] === "object") delete data[key];
        }

        if (!this.isPublished) data.active = 1;

        this.$rest(this.data.key)
          .put({ id: this.itemId, data })
          .then(() => this.$router.push(`/${this.data.key}`));
      }
    },
    setRangeDateActived() {
      this.updatingRangeDateActived = true;

      const data = Object.fromEntries(
        new FormData(this.$refs.rangeDateActiveForm.$el).entries()
      );

      this.$rest(this.data.key)
        .put({ id: this.itemId, save: () => false, data })
        .finally(() => (this.updatingRangeDateActived = false));
    },
  },
  created() {
    this.$rest(this.data.key)
      .get({ id: this.itemId })
      .catch(() => this.$router.replace("/" + this.data.key));
  },
  components: {
    ModuleTemplate,
    ToolbarButton,
    ...fieldsElements,
  },
};
</script>

<style>
.table-module-edit-form {
  display: grid;
  grid-template-columns: repeat(2, calc(50% - 16px / 2));
  column-gap: 16px;
  grid-auto-flow: dense;
}

.table-module-edit-rangedateactived input::-webkit-calendar-picker-indicator {
  filter: brightness(1) invert(1);
}
</style>
