<template lang="pug">
module-template(
  :title="`${data.name} / Transmissão`",
  width="800px",
  max-width="100%"
)
  v-card.pa-4.rounded-t-0(outlined, dark)
    v-form(ref="form")
      .table-module-edit-form
        tiny-text(name="subject", label="Assunto")
        big-text(ref="text", name="text", label="Texto")
      .d-flex.justify-end
        v-btn.text-none(
          @click="send",
          :loading="loading",
          color="secondary",
          depressed
        ) Transmitir
</template>

<script>
import ModuleTemplate from "@/components/templates/Module";
import TinyText from "../../../fields/components/tiny-text/Index.vue";
import BigText from "../../../fields/components/big-text/Index.vue";

export default {
  name: "NewsletterBroadcast",
  props: {
    data: Object,
  },
  data: () => ({
    loading: false,
  }),
  methods: {
    async send() {
      const form = this.$refs.form;

      const data = form && new FormData(form.$el);

      this.loading = true;

      await this.$rest(this.data.key).post({
        prop: "broadcast",
        data,
      });

      this.loading = false;
    },
  },
  components: {
    ModuleTemplate,
    TinyText,
    BigText,
  },
};
</script>

<style>
.table-module-edit-form {
  display: grid;
  grid-template-columns: repeat(2, calc(50% - 16px / 2));
  column-gap: 16px;
  grid-auto-flow: dense;
}

.table-module-edit-rangedateactived input::-webkit-calendar-picker-indicator {
  filter: brightness(1) invert(1);
}
</style>
