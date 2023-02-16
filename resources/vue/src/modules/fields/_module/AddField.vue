<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    max-width="340px"
  >
    <v-card class="pa-4" dark="dark">
      <div class="title text-center mb-4">Adicionar campo</div>
      <v-form ref="form">
        <v-row>
          <v-col class="py-0" cols="12" sm="6">
            <v-text-field
              v-model="name"
              :rules="[rules.required]"
              label="Nome"
              dense="dense"
              outlined="outlined"
            ></v-text-field>
          </v-col>
          <v-col class="py-0" cols="12" sm="6">
            <v-text-field
              v-model="key"
              @input="$emit('changekey')"
              :rules="[rules.required, rules.alphaNumUnderline]"
              label="Chave"
              dense="dense"
              outlined="outlined"
            ></v-text-field>
          </v-col>
          <v-col class="py-0" cols="12" sm="6">
            <v-select
              v-model="type"
              :items="types"
              :rules="[rules.required]"
              label="Tipo"
              dense="dense"
              outlined="outlined"
            ></v-select>
          </v-col>
          <v-col class="py-0" cols="12" sm="6">
            <div class="field-item-switches d-flex">
              <tooltip tip="Único" top="top">
                <v-btn @click="unique = !unique" icon="icon">
                  <v-icon color="cyan" :disabled="!unique"
                    >fas fa-fingerprint</v-icon
                  >
                </v-btn>
              </tooltip>
            </div>
          </v-col>
          <v-col class="py-0 mt-2 mt-sm-0" cols="12">
            <v-btn
              @click="send"
              :loading="sending"
              color="secondary darken-2"
              depressed="depressed"
              block="block"
              ><span class="text-none">Adicionar</span></v-btn
            >
          </v-col>
        </v-row>
      </v-form>
    </v-card>
  </v-dialog>
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import { required, alphaNumUnderline } from "@/components/forms/rules";

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
      alphaNumUnderline,
    },
    name: null,
    key: null,
    type: null,
    unique: false,
    sending: false,
  }),
  computed: {
    moduleId() {
      return this.$route.params.module;
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
