<template lang="pug">
module-template(
  :title="`${data.name} / Alterar`",
  width="800px",
  max-width="100%"
)
  v-card.pa-4.rounded-t-0(outlined, dark)
    v-form(ref="form")
      .table-module-add-form
        component(
          v-for="({ id, name, key, typeKey }, i) in data.fields",
          :value="item[key] || ''",
          :is="typeKey + 'field'",
          :label="name",
          :name="key",
          :key="i",
          :field-id="parseInt(id)"
        )
      .d-flex.justify-end
        v-btn.text-none(@click="send", color="secondary", depressed) {{ isPublished ? 'Alterar' : 'Publicar' }}
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import fieldsElements from "../../fields";

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
          .then(() => this.$router.push(`/admin/${this.data.key}`));
      }
    },
  },
  created() {
    this.$rest(this.data.key)
      .get({ id: this.itemId })
      .catch(() => this.$router.push("/admin/" + this.data.key));
  },
  components: {
    ModuleTemplate,
    ...fieldsElements,
  },
};
</script>

<style>
.table-module-add-form {
  display: grid;
  grid-template-columns: 1fr 1fr;
  column-gap: 16px;
  grid-auto-flow: dense;
}
</style>
