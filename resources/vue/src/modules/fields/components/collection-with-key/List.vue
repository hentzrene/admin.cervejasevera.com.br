<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="800px"
)
  v-card.pa-4(dark)
    .title.mb-4.d-flex.flex-column.flex-sm-row.justify-space-between
      div Coleção com chave
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
          .field-collectionwithkey-list-item.mt-1.py-2
            v-textarea.mb-2(
              :value="item.value[0]",
              :rows="1",
              dense,
              auto-grow,
              outlined,
              @input="(data) => (items_[item.id][0] = data)"
            )
            v-textarea(
              :value="item.value[1]",
              :rows="1",
              dense,
              auto-grow,
              @input="(data) => (items_[item.id][1] = data)"
            )
    .pt-8.pb-4.text-body-2.text-center.font-weight-bold(v-else) Nenhum item foi adicionado.
    v-btn.text-none.mt-2(@click="save", color="secondary", block, depressed) Salvar
  v-overlay(v-model="loading")
    loading
</template>

<script>
import Loading from "@/components/tools/Loading";

export default {
  props: {
    value: Boolean,
    inputName: {
      type: String,
      required: true,
    },
    items: {
      type: Object,
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
        value: item,
      }));
    },
  },
  methods: {
    add() {
      this.items_.push(["", ""]);
    },
    remove() {
      this.selecteds.map(({ id }) => {
        this.items_.splice(
          this.items_.findIndex((v) => v.id == id),
          1
        );
      });

      this.selecteds = [];
    },
    save() {
      const form = this.$refs.form;
      if (!form.validate()) return;

      const value = JSON.stringify(Object.fromEntries(this.items_));

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
          this.items_ = Object.entries(JSON.parse(value));
          this.selecteds = [];
          this.loading = false;
        });
    },
  },
  watch: {
    items() {
      this.items_ = Object.entries(this.items);
    },
  },
  mounted() {
    this.items_ = Object.entries(this.items);
  },
  components: {
    Loading,
  },
};
</script>

<style>
.field-collectionwithkey-list-item {
  grid-template-columns: auto;
}

.field-collectionwithkey-list-item .v-text-field__details {
  display: none;
}
.field-collectionwithkey-list-item .error--text .v-text-field__details {
  display: block;
}
</style>