<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="400px"
)
  v-card.pa-4(dark)
    .title.mb-4.d-flex.flex-column.flex-sm-row.justify-space-between
      div Coleção
      div
        v-btn.mr-2.text-none(
          @click="remove",
          color="secondary",
          :disabled="!selecteds.length",
          depressed,
          small
        )
          v-icon(left, small) fas fa-trash
          span Remover
        v-btn.text-none(@click="add", color="secondary", depressed, small)
          v-icon(left, small) fas fa-plus
          span Adicionar
    v-form(v-if="mappedItems.length", ref="form")
      v-data-table(
        v-model="selecteds",
        :headers="headers",
        :items="mappedItems",
        :mobile-breakpoint="0",
        no-data-text="Não há registros.",
        show-select,
        disable-pagination,
        hide-default-footer
      )
        template(#item.item="{ item }")
          component.field-collection-list-item.mt-1.py-2(
            :is="type.toLowerCase() + 'field'",
            :value="item.item",
            :name="item.id.toString()",
            @input="(data) => (items_[item.id] = data)",
            label=""
          )
    .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum item foi adicionado.
    v-btn.text-none.mt-2(@click="save", color="secondary", block, depressed) Salvar
  v-overlay(v-model="loading")
    loading
</template>

<script>
import fieldsElements from "../../../fields";
import Loading from "@/components/tools/Loading";

export default {
  props: {
    value: Boolean,
    inputName: {
      type: String,
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
    itemsFieldType: {
      type: String,
      required: true,
    },
  },
  data: () => ({
    loading: false,
    items_: [],
    selecteds: [],
    headers: [
      {
        text: "Item",
        value: "item",
        align: "left",
      },
    ],
  }),
  computed: {
    moduleKey() {
      return this.$rest("modules").item.key;
    },
    itemId() {
      return this.$route.params.sub || 1;
    },
    mappedItems() {
      return this.items_.map((item, i) => ({
        id: i,
        item,
      }));
    },
    type() {
      const type = this.$rest("modulesFieldsTypes").list.find(
        ({ id }) => id == this.itemsFieldType
      );

      return type && type.key;
    },
  },
  methods: {
    add() {
      this.items_.push("");
    },
    remove() {
      for (let { id } of this.selecteds) this.items_.splice(id, 1);
      this.selecteds = [];
    },
    save() {
      const form = this.$refs.form;
      if (!form.validate()) return;

      const value = JSON.stringify(
        Object.values(Object.fromEntries(new FormData(form.$el).entries()))
      );

      this.loading = true;

      this.$rest(this.moduleKey)
        .put({
          id: this.itemId,
          prop: this.inputName,
          data: {
            value,
          },
        })
        .then(() => this.$emit("saved"))
        .finally(() => {
          this.selecteds = [];
          this.loading = false;
        });
    },
  },
  watch: {
    items() {
      this.items_ = this.items;
    },
  },
  mounted() {
    this.items_ = this.items;
  },
  components: {
    Loading,
    ...fieldsElements,
  },
};
</script>

<style>
.field-collection-list-item .v-text-field__details {
  display: none;
}
.field-collection-list-item .error--text .v-text-field__details {
  display: block;
}
</style>
