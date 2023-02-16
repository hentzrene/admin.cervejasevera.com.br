<template>
  <v-dialog
    :value="value"
    @input="(data) => $emit('input', data)"
    :max-width="maxWidth"
  >
    <v-card class="pa-4" v-if="!loading" dark="dark">
      <div
        class="title mb-4 d-flex flex-column flex-sm-row"
        :class="simple ? 'justify-center' : 'justify-space-between'"
      >
        <div>{{ title }}</div>
        <div class="field-dialog-actions d-flex" v-if="!simple">
          <slot name="actions"></slot>
        </div>
      </div>
      <slot></slot>
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
    maxWidth: [String, Number],
    title: String,
    loading: Boolean,
    simple: {
      type: Boolean,
      value: false,
    },
  },
  components: {
    Loading,
  },
};
</script>

<style>
.field-dialog-actions {
  gap: 8px;
}
</style>
