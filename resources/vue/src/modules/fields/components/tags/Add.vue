<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="330px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title mb-4 text-center">Adicionar tag</div>
      <v-text-field
        class="mb-3"
        v-model="addTagTitle"
        label="Título"
        outlined="outlined"
        dense="dense"
        hide-details="hide-details"
      ></v-text-field>
      <v-btn
        class="text-none"
        @click="add"
        :disabled="!addTagTitle"
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
    addTagTitle: null,
  }),
  computed: {
    module() {
      return this.$rest("modules").list.find(
        ({ key }) => key === this.moduleKey
      );
    },
    moduleId() {
      if (!this.module) {
        return null;
      }

      return this.module.id;
    },
    moduleKey() {
      return this.$route.params.module;
    },
  },
  methods: {
    add() {
      this.loading = true;
      this.$rest("modulesTags")
        .post({
          data: {
            title: this.addTagTitle,
          },
          params: {
            moduleId: this.moduleId,
            fieldId: this.fieldId,
          },
        })
        .then((data) => this.$emit("add", data))
        .finally(() => {
          this.addTagTitle = null;
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
