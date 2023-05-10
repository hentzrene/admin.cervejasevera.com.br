<template>
  <template-dialog-any
    @input="(data) => $emit('input', data)"
    :value="value"
    title="Tags"
    max-width="500px"
  >
    <template #actions>
      <template-dialog-header-button
        v-if="isAdminUser"
        @click="linkModule ? unlink() : (linkModuleDialog = true)"
        :icon="linkModule ? 'fas fa-unlink' : 'fas fa-link'"
        :text="(linkModule ? 'Desvincular' : 'Vincular') + ' Módulo'"
        :disabled="linkModule ? false : Boolean(tags.length)"
      ></template-dialog-header-button>
      <template-dialog-header-button
        @click="remove"
        :disabled="!selecteds.length"
        icon="fas fa-trash"
        text="Remover"
      ></template-dialog-header-button>
      <template-dialog-header-button
        @click="addDialog = true"
        icon="fas fa-plus"
        text="Adicionar"
      ></template-dialog-header-button>
    </template>
    <v-data-table
      :headers="headers"
      :items="tags"
      :mobile-breakpoint="0"
      v-if="tags.length"
      v-model="selecteds"
      no-data-text="Não há registros."
      sort-by="id"
      sort-desc="sort-desc"
      show-select="show-select"
      disable-pagination="disable-pagination"
      hide-default-footer="hide-default-footer"
    >
      <template #item.title="{ item }">
        <v-edit-dialog
          class="primary lighten-4"
          @save="editTitle(item.id)"
          :return-value.sync="editTagTitle"
          dark="dark"
          >{{ item.title }}
          <template #input>
            <v-text-field
              :value="item.title"
              @input="(data) => (editTagTitle = data)"
              :rules="[rules.required]"
              label="Renomear"
              single-line="single-line"
              counter="counter"
            ></v-text-field>
          </template>
        </v-edit-dialog>
      </template>
    </v-data-table>
    <div class="pt-8 pb-4 text-body-2 text-center font-weight-bold" v-else>
      Nenhuma tag foi adicionada.
    </div>
    <v-overlay v-if="value" v-model="loading">
      <loading></loading>
    </v-overlay>
    <add v-model="addDialog" :field-id="fieldId"></add>
    <link-module
      v-model="linkModuleDialog"
      :field-id="fieldId"
      :link-module="linkModule"
    ></link-module>
  </template-dialog-any>
</template>

<script>
import TemplateDialogAny from "../../templates/DialogAny";
import TemplateDialogHeaderButton from "../../templates/DialogHeaderButton";
import Add from "./Add";
import LinkModule from "./LinkModule";
import Loading from "@/components/tools/Loading";
import { required } from "@/components/forms/rules.js";

export default {
  props: {
    value: Boolean,
    fieldId: {
      type: Number,
      required: true,
    },
    tags: {
      type: Array,
      default: () => [],
    },
    isAdminUser: {
      type: Boolean,
      default: false,
    },
    linkModule: String,
  },
  data: () => ({
    addDialog: false,
    linkModuleDialog: false,
    loading: false,
    headers: [
      {
        text: "Título",
        value: "title",
        align: "left",
      },
    ],
    editTagTitle: null,
    rules: {
      required,
    },
    selecteds: [],
  }),
  computed: {
    module() {
      return this.$rest("modules").list.find(
        ({ key }) => key === this.moduleKey
      );
    },
    moduleId() {
      if (!this.module) {
        return null;
      }

      return this.module.id;
    },
    moduleKey() {
      return this.$route.params.module;
    },
    itemId() {
      return this.$route.params.sub || 1;
    },
  },
  methods: {
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest("modulesTags").delete({
            id: id,
            params: { moduleId: this.moduleId },
          })
        )
      ).finally(() => {
        this.selecteds = [];
        this.loading = false;
      });
    },
    editTitle(tagId) {
      const currentTagTitle = this.tags.find(({ id }) => id == tagId).title;
      if (!this.editTagTitle || currentTagTitle === this.editTagTitle) return;

      this.loading = true;

      this.$rest("modulesTags")
        .put({
          id: tagId,
          prop: "title",
          data: {
            value: this.editTagTitle,
          },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() => (this.loading = false));
    },
    unlink() {
      this.loading = true;

      this.$rest("modulesCategories")
        .put({
          id: "unlink-module",
          data: {
            link: null,
          },
          params: {
            fieldId: this.fieldId,
            moduleId: this.moduleId,
          },
        })
        .finally(() => {
          this.$router.go();
        });
    },
  },
  components: {
    TemplateDialogAny,
    TemplateDialogHeaderButton,
    Loading,
    Add,
    LinkModule,
  },
};
</script>
