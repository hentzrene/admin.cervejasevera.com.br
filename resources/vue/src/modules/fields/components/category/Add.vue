<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="330px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title mb-4 text-center">Adicionar categoria</div>
      <v-text-field
        class="mb-3"
        v-model="addCategoryTitle"
        label="Título"
        outlined="outlined"
        dense="dense"
        hide-details="hide-details"
      ></v-text-field>
      <v-btn
        class="text-none"
        @click="add"
        :disabled="!addCategoryTitle"
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
    fieldId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    loading: false,
    addCategoryTitle: null,
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
  },
  methods: {
    add() {
      this.loading = true;
      this.$rest("modulesCategories")
        .post({
          data: {
            title: this.addCategoryTitle,
          },
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
          },
        })
        .then((data) => this.$emit("add", data))
        .finally(() => {
          this.addCategoryTitle = null;
          this.addDialog = false;
          this.loading = false;
        });
    },
  },
  components: {
    Loading,
  },
};
</script>
