<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="800px"
  >
    <v-card class="pa-4" dark="dark">
      <div
        class="title mb-4 d-flex flex-column flex-sm-row justify-space-between"
      >
        <div>Coleção</div>
        <div>
          <v-btn
            class="mr-2 text-none"
            @click="remove"
            color="secondary"
            :disabled="!selecteds.length"
            depressed="depressed"
            small="small"
          >
            <v-icon left="left" small="small">fas fa-trash</v-icon
            ><span>Remover</span>
          </v-btn>
          <v-btn
            class="text-none"
            @click="add"
            color="secondary"
            depressed="depressed"
            small="small"
          >
            <v-icon left="left" small="small">fas fa-plus</v-icon
            ><span>Adicionar</span>
          </v-btn>
        </div>
      </div>
      <v-form v-if="mappedItems.length" ref="form">
        <v-data-table
          v-model="selecteds"
          :headers="headers"
          :items="mappedItems"
          :mobile-breakpoint="0"
          no-data-text="Não há registros."
          show-select="show-select"
          disable-pagination="disable-pagination"
          hide-default-footer="hide-default-footer"
        >
          <template #item.item="{ item }">
            <v-textarea
              class="field-collection-list-item mt-1 py-2"
              :value="item.value"
              :rows="1"
              dense="dense"
              auto-grow="auto-grow"
              @input="(data) => (items_[item.id] = data)"
            ></v-textarea>
          </template>
        </v-data-table>
      </v-form>
      <div class="pt-8 pb-4 text-body-2 text-center font-weight-bold" v-else>
        Nenhum item foi adicionado.
      </div>
      <v-btn
        class="text-none mt-2"
        @click="save"
        color="secondary"
        block="block"
        depressed="depressed"
        >Salvar</v-btn
      >
    </v-card>
    <v-overlay v-model="loading">
      <loading></loading>
    </v-overlay>
  </v-dialog>
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
      type: Array,
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
      return this.$route.params.module;
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
      this.items_.push("");
    },
    remove() {
      this.items_ = this.items_.filter(
        (v, i) => !this.selecteds.find(({ id }) => id === i)
      );

      this.selecteds = [];
    },
    save() {
      const form = this.$refs.form;

      if (form && !form.validate()) return;

      const value = JSON.stringify(this.items_);

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
