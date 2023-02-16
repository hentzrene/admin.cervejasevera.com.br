<template>
  <v-card class="pa-3" color="primary">
    <v-form class="field-item" ref="form">
      <div class="field-item-inputs align-center">
        <v-text-field
          @keydown.enter="updateProperty($event.target.value, 'name')"
          @blur="updateProperty($event.target.value, 'name')"
          :value="data.name"
          :loading="updating.name"
          :disabled="updating.name || updating.typeId || removing"
          label="Nome"
          dense="dense"
          solo="solo"
          hide-details="hide-details"
        ></v-text-field>
        <v-select
          @input="updateProperty($event, 'typeId')"
          :value="data.typeId"
          :items="types"
          :loading="updating.typeId"
          :disabled="updating.name || updating.typeId || removing"
          label="Tipo"
          dense="dense"
          solo="solo"
          hide-details="hide-details"
        ></v-select>
        <v-btn @click="dialog = true" small="small" icon="icon">
          <v-icon small="small">fas fa-cog</v-icon>
        </v-btn>
        <v-btn
          @click="remove"
          :loading="removing"
          color="secondary darken-2"
          small="small"
          icon="icon"
        >
          <v-icon small="small">fas fa-trash</v-icon>
        </v-btn>
      </div>
    </v-form>
    <v-dialog v-model="dialog" max-width="400px">
      <v-card class="pa-4" dark="dark">
        <div class="title d-flex">Seção</div>
        <v-data-table
          class="py-4"
          v-model="settedSectionField"
          :headers="sectionsTableHeaders"
          :items="sections"
          :loading="sectionDataTableLoading"
          single-select="single-select"
          show-select="show-select"
          hide-default-header="hide-default-header"
          hide-default-footer="hide-default-footer"
        >
          <template #item.title="{ item }">
            <v-edit-dialog
              @save="updateSectionTitle"
              @open="openDialogUpdateSectionTitle(item)"
              dark="dark"
              >{{ item.title }}
              <template #input>
                <v-text-field
                  v-model="editUpdateSectionTitle"
                  :value="item.title"
                  label="Editar"
                  single-line="single-line"
                  counter="counter"
                ></v-text-field>
              </template>
            </v-edit-dialog>
          </template>
          <template #item.actions="{ item }">
            <v-btn
              @click="removeSection(item.id)"
              :disabled="sectionDataTableLoading"
              color="secondary darken-2"
              small="small"
              icon="icon"
            >
              <v-icon small="small">fas fa-trash</v-icon>
            </v-btn>
          </template>
        </v-data-table>
        <div class="add-section py-1">
          <v-icon class="pa-3" small="small">fas fa-plus</v-icon>
          <input
            v-model="addSectionValue"
            @keydown.enter="addSection"
            placeholder="Adicionar Seção"
          />
        </div>
      </v-card>
    </v-dialog>
  </v-card>
</template>

<script>
import { required, alphaNumUnderline } from "@/components/forms/rules";

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
        modules_sections_fields_id: null,
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
      alphaNumUnderline,
    },
    removing: false,
    updating: {
      name: false,
      typeId: false,
    },
    dialog: false,
    sectionsTableHeaders: [
      { text: "Título", value: "title", align: "start" },
      { text: "Ações", value: "actions", align: "end" },
    ],
    selectedUpdateSection: null,
    editUpdateSectionTitle: null,
    sectionDataTableLoading: false,
    addSectionValue: null,
  }),
  computed: {
    moduleId() {
      return this.$route.params.module;
    },
    sections() {
      return this.$rest("modulesSectionsFields").list;
    },
    settedSectionField: {
      get() {
        if (!this.data) return [];

        const section = this.sections.find(
          ({ id }) => id === this.data.modules_sections_fields_id
        );

        if (!section) return [];

        return [section];
      },
      set(val) {
        if (!val[0]) {
          this.updateProperty(null, "modules_sections_fields_id");
          return;
        }

        this.updateProperty(val[0].id, "modules_sections_fields_id");
      },
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
      this.sectionDataTableLoading = true;

      this.$rest("modulesFields")
        .put({
          id: this.data.id,
          data: { value: value },
          prop,
          params: { moduleId: this.moduleId },
        })
        .then(() => (this.data[prop] = value))
        .finally(() => {
          this.updating[prop] = false;
          this.sectionDataTableLoading = false;
        });
    },
    addSection() {
      this.sectionDataTableLoading = true;

      this.$rest("modulesSectionsFields")
        .post({
          data: {
            title: this.addSectionValue,
            moduleId: this.moduleId,
          },
        })
        .then((e) => {
          if (e.status !== "error") {
            this.addSectionValue = "";
          }
        })
        .finally(() => {
          this.sectionDataTableLoading = false;
        });
    },
    removeSection(sectionId) {
      this.sectionDataTableLoading = true;
      this.$rest("modulesSectionsFields")
        .delete({ id: sectionId, params: { moduleId: this.moduleId } })
        .finally(() => (this.sectionDataTableLoading = false));
    },
    openDialogUpdateSectionTitle(item) {
      this.selectedUpdateSection = item;
      this.editUpdateSectionTitle = item.title;
    },
    updateSectionTitle() {
      if (this.selectedUpdateSection.title === this.editUpdateSectionTitle)
        return false;

      this.sectionDataTableLoading = true;
      this.$rest("modulesSectionsFields")
        .put({
          id: this.selectedUpdateSection.id,
          data: { title: this.editUpdateSectionTitle },
          params: { moduleId: this.moduleId },
        })
        .then(() => {
          this.selectedUpdateSection.title = this.editUpdateSectionTitle;
          this.sectionDataTableLoading = false;
        });
    },
  },
};
</script>

<style>
.field-item-inputs {
  display: grid;
  grid-template-columns: 1fr 1fr max-content max-content;
  gap: 8px;
}

.add-section {
  display: flex;
  background-color: #333;
}
.add-section input:focus {
  outline: none;
}
.add-section input {
  color: white;
}
</style>
