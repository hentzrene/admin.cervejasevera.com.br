<template lang="pug">
module-template.page-tags(title="Tags", max-width="800px")
  v-card.pa-4.pb-0.rounded-t-0(outlined, dark)
    v-form.pt-4.d-flex.flex-column(ref="form")
      v-textarea(
        :value="configuration.usanalyticser",
        :disabled="loading",
        :loading="loading",
        :rows="10",
        label="Analytics",
        name="analytics",
        outlined,
        dense
      )
      v-textarea(
        :value="configuration.ads",
        :disabled="loading",
        :loading="loading",
        :rows="10",
        label="Ads",
        name="ads",
        outlined,
        dense
      )
      v-textarea(
        :value="configuration.facebookPixel",
        :disabled="loading",
        :loading="loading",
        :rows="10",
        label="Facebook Pixel",
        name="facebookPixel",
        outlined,
        dense
      )
    v-card-actions
      v-btn.text-none.ml-auto(
        @click="submit",
        :disabled="loading",
        :loading="loading",
        color="secondary",
        depressed
      ) Salvar
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";

export default {
  data: () => ({
    loading: true,
  }),
  computed: {
    configuration() {
      return this.$rest("configuration").item;
    },
  },
  methods: {
    submit() {
      const form = this.$refs.form;
      if (form.validate()) {
        this.loading = true;

        this.$rest("configuration")
          .put({ id: "tags", data: new FormData(form.$el) })
          .finally(() => (this.loading = false));
      }
    },
  },
  created() {
    if (this.$store.state.user.type != 1) {
      this.$router.replace("/error404");
      return;
    }

    this.loading = true;
    this.$rest("configuration")
      .get({ id: "tags", save: (state, data) => (state.item = data) })
      .finally(() => (this.loading = false));
  },
  components: {
    ModuleTemplate,
  },
};
</script>

<style>
.page-tags textarea {
  font-family: monospace;
  line-height: 20px;
}
</style>
