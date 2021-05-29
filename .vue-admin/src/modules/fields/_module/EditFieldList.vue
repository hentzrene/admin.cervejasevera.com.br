<template lang="pug">
div
  .field-list.d-flex.flex-wrap(ref="fieldList")
    field-item(
      v-for="(field, i) in items",
      @remove="removeField(i)",
      @changekey="$emit('changefieldkey')",
      :types="fieldsTypes",
      :key="i",
      :data="field"
    )
  .pt-8.text-body-2.text-center.font-weight-bold.red--text(v-if="repeteadKey") Não pode existir alguma chave de campo repetida.
</template>

<script>
import FieldItem from "./EditFieldItem";

export default {
  props: {
    items: {
      type: Array,
      default: () => [],
    },
  },
  data: () => ({
    selected: null,
    repeteadKey: false,
  }),
  computed: {
    fieldsTypes() {
      return this.$rest("modulesFieldsTypes").list.map(({ id, name }) => ({
        text: name,
        value: id,
      }));
    },
  },
  methods: {
    removeField(i) {
      this.items.splice(i, 1);
    },
    validate() {
      const fieldsValiditys = [];
      this.$children.forEach((c) => fieldsValiditys.push(c.validate()));
      if (fieldsValiditys.includes(false)) return false;

      const fieldsKeys = [];
      this.repeteadKey = false;
      for (const { key } of this.items) {
        if (fieldsKeys.includes(key)) {
          this.repeteadKey = true;
          return false;
        }
        fieldsKeys.push(key);
      }

      return true;
    },
  },
  beforeCreate() {
    this.$rest("modulesFieldsTypes").get();
  },
  components: {
    FieldItem,
  },
};
</script>

<style>
.field-list {
  gap: 20px;
}
</style>
