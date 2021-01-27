
<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  max-width="340px"
)
  v-card.pa-4(dark)
    .title.text-center.mb-4 Adicionar campo
    v-form(ref="form")
      v-row
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            v-model="name",
            :rules="[rules.required]",
            label="Nome",
            dense,
            outlined
          )
        v-col.py-0(cols=12, sm=6)
          v-text-field(
            v-model="key",
            @input="$emit('changekey')",
            :rules="[rules.required, rules.lowerCase]",
            label="Chave",
            dense,
            outlined
          )
        v-col.py-0(cols=12, sm=6)
          v-select(
            v-model="type",
            :items="types",
            :rules="[rules.required]",
            label="Tipo",
            dense,
            outlined
          )
        v-col.py-0(cols=12, sm=6)
          .field-item-switches.d-flex
            tooltip(tip="Único", top)
              v-btn(@click="unique = !unique", icon)
                v-icon(color="cyan", :disabled="!unique") fas fa-fingerprint
            tooltip(tip="Privado", top)
              v-btn(@click="private = !private", icon)
                v-icon(color="green", :disabled="!private") fas fa-shield-alt
        v-col.py-0.mt-2.mt-sm-0(cols=12)
          v-btn(
            @click="send",
            :loading="sending",
            color="secondary darken-2",
            depressed,
            block
          )
            span.text-none Adicionar
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import { required, lowerCase } from "@/components/forms/rules";

export default {
  props: {
    value: {
      type: Boolean,
      default: false,
    },
    types: {
      type: Array,
      required: true,
    },
  },
  data: () => ({
    rules: {
      required,
      lowerCase,
    },
    name: null,
    key: null,
    type: null,
    unique: false,
    private: false,
    sending: false,
  }),
  computed: {
    moduleId() {
      return this.$route.params.sub;
    },
  },
  methods: {
    send() {
      const form = this.$refs.form;
      if (form.validate()) {
        this.sending = true;
        this.$rest("modulesFields")
          .post({
            data: {
              name: this.name,
              key: this.key,
              type: this.type,
              unique: this.unique,
              private: this.private,
            },
            params: { moduleId: this.moduleId },
          })
          .then(() =>
            this.$rest("modules")
              .get({ id: this.moduleId })
              .then(() => {
                this.$emit("input", false);
                this.sending = false;
              })
          )
          .catch(() => (this.sending = false));
      }
    },
  },
  components: {
    Tooltip,
  },
};
</script>

<style>
.field-item-switches {
  grid-area: switches;
  gap: 4px;
}
</style>
