<template lang="pug">
module-template.page-tags(title="Tags", max-width="800px")
  v-card.pa-4.pb-0.rounded-t-0(outlined, dark)
    v-form.pt-4.d-flex.flex-column(ref="form")
      v-textarea(
        :value="configuration.head_after_begin",
        :disabled="loading",
        :loading="loading",
        :rows="10",
        label="HEAD - Depois de começar",
        name="head_after_begin",
        outlined,
        dense
      )
      v-textarea(
        :value="configuration.head_before_end",
        :disabled="loading",
        :loading="loading",
        :rows="10",
        label="HEAD - Antes do fim",
        name="head_before_end",
        outlined,
        dense
      )
      v-textarea(
        :value="configuration.body_after_begin",
        :disabled="loading",
        :loading="loading",
        :rows="10",
        label="BODY - Depois de começar",
        name="body_after_begin",
        outlined,
        dense
      )
      v-textarea(
        :value="configuration.body_before_end",
        :disabled="loading",
        :loading="loading",
        :rows="10",
        label="BODY - Antes do fim",
        name="body_before_end",
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
  line-height: 18px !important;
}
</style>
