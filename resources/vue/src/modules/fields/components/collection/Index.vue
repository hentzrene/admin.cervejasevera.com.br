<template>
  <grid-item class="mb-2" row-end="span 2" col-end="span 2" col-end-sm="span 1">
    <div
      class="grey--text text--lighten-1 font-weight-bold text-caption d-flex align-center mb-1"
    >
      {{ label }}
    </div>
    <tooltip :tip="label" top="top">
      <v-responsive
        class="grey d-flex align-center rounded-lg primary cursor-pointer"
        @click="listDialog = true"
        :aspect-ratio="38 / 9"
        v-ripple="v - ripple"
      >
        <div class="d-flex justify-center align-center">
          <div class="d-flex flex-column">
            <v-icon class="d-block mx-auto" :size="30"
              >fas fa-layer-group</v-icon
            >
            <div class="text-caption font-weight-bold mt-1">Alterar</div>
          </div>
        </div>
      </v-responsive>
    </tooltip>
    <list
      v-model="listDialog"
      @saved="listDialog = false"
      :items="parsedValue"
      :input-name="name"
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
    listDialog: false,
  }),
  computed: {
    moduleId() {
      return parseInt(this.$rest("modules").item.id);
    },
    parsedValue() {
      return JSON.parse(this.value || "[]");
    },
  },
  beforeCreate() {
    this.$rest("modulesFieldsTypes").get();
  },
  mixins: [mixin],
  components: {
    Tooltip,
    List,
  },
};
</script>
