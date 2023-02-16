<template>
  <grid-item class="d-flex" col-end="span 2" col-end-sm="span 1">
    <v-menu offset-y="offset-y" :close-on-content-click="false">
      <template #activator="{ on, attrs }">
        <v-btn
          class="mt-1 mr-2"
          v-bind="attrs"
          v-on="on"
          :color="value_"
          fab="fab"
          depressed="depressed"
          x-small="x-small"
        >
          <v-icon>fas fa-tint</v-icon>
        </v-btn>
      </template>
      <v-color-picker v-model="value_" mode="hexa" dark="dark"></v-color-picker>
    </v-menu>
    <v-text-field
      :label="label"
      :value="value_"
      :name="name"
      readonly="readonly"
      dense="dense"
      outlined="outlined"
      dark="dark"
    ></v-text-field>
  </grid-item>
</template>

<script>
import mixin from "../../mixin";

export default {
  data: () => ({
    picker: false,
    value_: null,
  }),
  mounted() {
    this.value_ = this.value;
  },
  watch: {
    value(v) {
      this.value_ = v;
    },
  },
  mixins: [mixin],
};
</script>
