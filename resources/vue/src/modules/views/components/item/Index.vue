<template lang="pug">
module-template(
  :title="`${data.name} / Alterar`",
  width="800px",
  max-width="100%"
)
  v-card.pa-4.rounded-t-0(outlined, dark)
    v-form(ref="form")
      div(v-for="fields, section in sectionedFields" :key="section").mb-6
        template(v-if="section !== 'null'")
          div.text-body-1.text-uppercase.font-weight-bold {{ section }}
          .item-module-add-form.mt-2.primary.lighten-2.pa-2.rounded
            component(
              v-for="({ id, name, key, typeKey, options }, i) in fields",
              :value="item[key] || ''",
              :is="typeKey.toLowerCase() + 'field'",
              :label="name",
              :name="key",
              :key="i",
              :field-id="parseInt(id)",
              :field-options="options"
            )
        .item-module-add-form.mt-2.rounded(v-else)
          component(
            v-for="({ id, name, key, typeKey, options }, i) in fields",
            :value="item[key] || ''",
            :is="typeKey.toLowerCase() + 'field'",
            :label="name",
            :name="key",
            :key="i",
            :field-id="parseInt(id)",
            :field-options="options"
          )
      .d-flex.justify-end
        v-btn.text-none(@click="send", color="secondary", depressed) Alterar
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import fieldsElements from "../../../fields";
import { groupBy } from "../../../../components/utils";

export default {
  name: "ItemIndex",
  props: {
    data: Object,
  },
  data: () => ({
    itemId: 1,
  }),
  computed: {
    item() {
      return this.$rest(this.data.key).item;
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

        this.$rest(this.data.key)
          .put({ id: this.itemId, data })
          .then(() => this.$router.push("/"));
      }
    },
  },
  created() {
    this.$rest(this.data.key)
      .get({ save: (state, data) => (state.item = data) })
      .catch(() => this.$router.replace("/"));
  },
  components: {
    ModuleTemplate,
    ...fieldsElements,
  },
};
</script>

<style>
.item-module-add-form {
  display: grid;
  grid-template-columns: repeat(2, calc(50% - 16px / 2));
  column-gap: 16px;
  grid-auto-flow: dense;
}
</style>
