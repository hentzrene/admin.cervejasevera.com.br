<template lang="pug">
v-dialog(
  :value="value",
  @input="(data) => $emit('input', data)",
  :max-width="maxWidth"
)
  v-card.pa-4(v-if="!loading", dark)
    .title.mb-4.d-flex.flex-column.flex-sm-row(
      :class="simple ? 'justify-center' : 'justify-space-between'"
    )
      div {{ title }}
      .field-dialog-actions.d-flex(v-if="!simple")
        slot(name="actions")
    slot
  v-overlay(v-if="value", v-model="loading")
    v-progress-circular(:size="50", color="secondary", indeterminate)
</template>

<script>
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
};
</script>

<style>
.field-dialog-actions {
  gap: 8px;
}
</style>
