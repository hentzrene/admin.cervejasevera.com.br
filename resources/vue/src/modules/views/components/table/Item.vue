<template>
  <module-template
    :title="`${data.name} / Alterar`"
    width="800px"
    max-width="100%"
  >
    <template #toolbar>
      <RangeDateButton :item="item" :item-id="itemId" :data="data" />
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
                :field-type-key="typeKey"
                :field-options="options"
                :item="item"
                :item-id="itemId"
                :module-id="moduleId"
                :module-key="moduleKey"
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
              :field-type-key="typeKey"
              :field-options="options"
              :item="item"
              :item-id="itemId"
              :module-id="moduleId"
              :module-key="moduleKey"
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
import fieldsElements from "../../../fields";
import { groupBy } from "../../../../components/utils";
import RangeDateButton from "./RangeDateButton.vue";

export default {
  name: "TableAdd",
  props: {
    data: Object,
  },
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
    moduleId() {
      return this.$rest("modules").item.id;
    },
    moduleKey() {
      return this.$route.params.module;
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
  },
  created() {
    this.$rest(this.data.key)
      .get({ id: this.itemId })
      .catch(() => this.$router.replace("/" + this.data.key));
  },
  components: {
    ModuleTemplate,
    ...fieldsElements,
    RangeDateButton,
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
</style>
