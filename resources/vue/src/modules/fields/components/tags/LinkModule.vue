<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="330px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title mb-4 text-center">Vincular módulo</div>
      <v-select
        class="mb-3"
        v-model="selectedModule"
        :items="modules"
        label="Módulo"
        outlined="outlined"
        dense="dense"
        hide-details="hide-details"
      ></v-select>
      <v-btn
        class="text-none"
        @click="link"
        :disabled="!selectedModule"
        color="secondary"
        block="block"
        depressed="depressed"
        >Vincular</v-btn
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
    selectedModule: null,
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
    modules() {
      return this.$rest("modules")
        .list.filter(
          ({ id, viewKey }) =>
            viewKey === "table" && id != this.$rest("modules").item.id
        )
        .map(({ name, key }) => ({
          text: name,
          value: key,
        }));
    },
  },
  methods: {
    link() {
      this.loading = true;

      this.$rest("modulesTags")
        .put({
          id: "link-module",
          data: {
            link: this.selectedModule,
          },
          params: {
            fieldId: this.fieldId,
            moduleId: this.moduleId,
          },
        })
        .finally(() => {
          this.$router.go();
        });
    },
  },
  components: {
    Loading,
  },
};
</script>
