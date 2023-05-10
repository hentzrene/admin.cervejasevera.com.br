<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="330px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title mb-4 text-center">Alterar Título</div>
      <v-text-field
        class="mb-3"
        :value="img.title"
        @input="(v) => (title = v)"
        label="Título"
        outlined="outlined"
        dense="dense"
        hide-details="hide-details"
      ></v-text-field>
      <v-btn
        class="text-none"
        @click="submit"
        :disabled="!title"
        color="secondary"
        block="block"
        depressed="depressed"
        >Alterar</v-btn
      >
    </v-card>
    <v-overlay v-if="value" v-model="loading">
      <Loading></Loading>
    </v-overlay>
  </v-dialog>
</template>

<script>
import Loading from "@/components/tools/Loading";

export default {
  props: {
    value: Boolean,
    img: {
      type: Object,
      required: true,
    },
  },
  data: () => ({
    loading: false,
    title: null,
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
    submit() {
      this.loading = true;
      this.$rest("modulesImages")
        .put({
          id: this.img.id,
          prop: "title",
          data: {
            value: this.title,
          },
        })
        .then(() => {
          this.img.title = this.title;
          this.$emit("input", false);
        })
        .finally(() => (this.loading = false));
    },
  },
  components: {
    Loading,
  },
};
</script>
