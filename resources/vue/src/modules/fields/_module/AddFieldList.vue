<template>
  <div>
    <div class="field-list" ref="fieldList">
      <field-item
        v-for="(field, i) in items"
        @remove="removeField(i)"
        @changekey="$emit('changefieldkey')"
        :types="fieldsTypes"
        :key="i"
        :data="field"
        :unique-option="uniqueOption"
      ></field-item>
    </div>
    <div
      class="pt-8 text-body-2 text-center font-weight-bold red--text"
      v-if="repeteadKey"
    >
      Não pode existir alguma chave de campo repetida.
    </div>
  </div>
</template>

<script>
import FieldItem from "./AddFieldItem";

export default {
  props: {
    items: {
      type: Array,
      default: () => [],
    },
    uniqueOption: {
      type: Boolean,
      default: true,
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
  display: grid;
  grid-template-columns: 1fr 1fr 1fr;
  gap: 20px;
}

@media screen and (min-width: 1904px) {
  .field-list {
    grid-template-columns: 1fr 1fr 1fr 1fr;
  }
}

@media screen and (max-width: 1264px) {
  .field-list {
    grid-template-columns: 1fr 1fr;
  }
}

@media screen and (max-width: 600px) {
  .field-list {
    grid-template-columns: 1fr;
  }
}
</style>
