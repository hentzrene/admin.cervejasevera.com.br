import { groupBy } from "../../../../components/utils"; import { groupBy } from
"../../../../components/utils";
<template lang="pug">
module-template(
  :title="`${data.name} / Alterar`",
  width="800px",
  max-width="100%"
)
  template(#toolbar)
    toolbar-button(
      @click="rangeDateActiveDialog = true",
      tip="Começo/Fim",
      icon="fas fa-calendar-alt"
    )
    v-dialog(v-model="rangeDateActiveDialog", max-width="330px")
      v-card.pa-4(dark)
        .title.mb-4.text-center Alterar período ativo
        v-form.table-module-edit-rangedateactived(ref="rangeDateActiveForm")
          v-text-field.mb-3(
            label="Começo",
            type="datetime-local",
            name="showFrom",
            :value="item.showFrom && item.showFrom.replace(' ', 'T')",
            :clearable="true",
            clear-icon="fas fa-times",
            outlined,
            dense,
            hide-details
          )
          v-text-field.mb-3(
            label="Fim",
            type="datetime-local",
            name="showUp",
            :value="item.showUp && item.showUp.replace(' ', 'T')",
            :clearable="true",
            clear-icon="fas fa-times",
            outlined,
            dense,
            hide-details
          )
        v-btn.text-none(
          @click="setRangeDateActived",
          color="secondary",
          :loading="updatingRangeDateActived",
          :disabled="updatingRangeDateActived",
          block,
          depressed
        ) Alterar
  v-card.pa-2.rounded-t-0(outlined, dark)
    v-form(ref="form")
      div(v-for="fields, section in sectionedFields" :key="section").mb-6
        template(v-if="section !== 'null'")
          div.text-body-1.text-uppercase.font-weight-bold {{ section }}
          .table-module-edit-form.mt-2.primary.lighten-2.pa-2.rounded
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
        .table-module-edit-form.mt-2.rounded(v-else)
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
        v-btn.text-none(@click="send", color="secondary", depressed) {{ isPublished ? 'Alterar' : 'Publicar' }}
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
