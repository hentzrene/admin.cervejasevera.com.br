<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="330px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title mb-4 text-center">Adicionar menu</div>
      <v-text-field
        class="mb-3"
        v-model="addMenuTitle"
        label="Título"
        outlined="outlined"
        dense="dense"
        hide-details="hide-details"
      ></v-text-field>
      <v-btn
        class="text-none"
        @click="add"
        :disabled="!addMenuTitle"
        color="secondary"
        block="block"
        depressed="depressed"
        >Adicionar</v-btn
      >
    </v-card>
    <v-overlay v-if="value" v-model="loading">
      <loading></loading>
    </v-overlay>
  </v-dialog>
</template>

<script>
import Loading from "@/components/tools/Loading";

export default {
  props: {
    value: Boolean,
  },
  data: () => ({
    loading: false,
    addMenuTitle: null,
  }),
  methods: {
    add() {
      this.loading = true;
      this.$rest("modulesMenu")
        .post({
          data: {
            title: this.addMenuTitle,
          },
        })
        .then((data) => this.$emit("add", data))
        .finally(() => {
          this.addMenuTitle = null;
          this.loading = false;
        });
    },
  },
  components: {
    Loading,
  },
};
</script>
