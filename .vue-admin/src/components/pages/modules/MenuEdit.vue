<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="330px"
)
  v-card.pa-4(dark)
    .title.mb-4.text-center Definir menu
    v-select.mb-3(
      v-model="menuSelected",
      :items="menusSelect",
      label="Título",
      outlined,
      dense,
      hide-details
    )
    v-btn.text-none(
      @click="save",
      :disabled="loading || sameValue",
      color="secondary",
      block,
      depressed
    ) Salvar
  v-overlay(v-if="value", v-model="loading")
    loading
</template>

<script>
import Loading from "@/components/tools/Loading";

export default {
  props: {
    value: Boolean,
    menus: Array,
    moduleItem: Object,
  },
  data: () => ({
    loading: false,
    menuSelected: null,
  }),
  computed: {
    menusSelect() {
      const r = this.$rest("modulesMenu").list.map(({ id, title }) => ({
        text: title,
        value: id,
      }));

      r.unshift({ text: "(Nenhum)", value: null });

      return r;
    },
    sameValue() {
      return this.moduleItem
        ? this.moduleItem.menuId === this.menuSelected
        : false;
    },
  },
  methods: {
    save() {
      this.loading = true;

      this.$rest("modules")
        .put({
          id: this.moduleItem.id,
          prop: "menu",
          data: {
            value: this.menuSelected,
          },
        })
        .then(() => {
          this.loading = false;

          if (this.menuSelected) {
            this.moduleItem.menuTitle = this.menus.find(
              ({ id }) => this.menuSelected == id
            ).title;
            this.moduleItem.menuId = this.menuSelected;
          } else {
            this.moduleItem.menuTitle = null;
            this.moduleItem.menuId = null;
          }

          this.$emit("input", false);
        });
    },
  },
  components: {
    Loading,
  },
};
</script>
