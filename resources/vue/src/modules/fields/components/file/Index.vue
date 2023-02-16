<template>
  <grid-item class="mb-2" row-end="span 2" col-end="span 2" col-end-sm="span 1">
    <div class="grey--text text--lighten-1 font-weight-bold text-caption">
      {{ label }}
    </div>
    <tooltip :tip="label" top="top">
      <v-responsive
        class="grey d-flex align-center rounded-lg primary cursor-pointer"
        @click="dialog = true"
        :aspect-ratio="38 / 9"
        v-ripple="v - ripple"
      >
        <div class="d-flex justify-center align-center">
          <div class="d-flex flex-column">
            <v-icon class="d-block mx-auto" :size="30">fas fa-file</v-icon>
            <div class="text-caption font-weight-bold mt-1">Enviar</div>
          </div>
        </div>
      </v-responsive>
    </tooltip>
    <list
      v-model="dialog"
      :input-name="name"
      :highlighted-file="value"
      :field-id="fieldId"
    ></list>
  </grid-item>
</template>

<script>
import Tooltip from "@/components/tools/Tooltip";
import List from "./List";
import mixin from "../../mixin";

export default {
  props: {
    fieldId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    dialog: false,
  }),
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
