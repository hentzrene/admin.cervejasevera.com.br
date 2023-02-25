<template>
  <div>
    <ToolbarButton
      @click="rangeDateActiveDialog = true"
      tip="Começo/Fim"
      icon="fas fa-calendar-alt"
    ></ToolbarButton>
    <v-dialog v-model="rangeDateActiveDialog" max-width="330px">
      <v-card class="pa-4" dark="dark">
        <div class="title mb-4 text-center">Alterar período ativo</div>
        <v-form
          class="table-module-edit-rangedateactived"
          ref="rangeDateActiveForm"
        >
          <v-text-field
            class="mb-3"
            label="Começo"
            type="datetime-local"
            name="showFrom"
            :value="item.showFrom && item.showFrom.replace(' ', 'T')"
            :clearable="true"
            clear-icon="fas fa-times"
            outlined="outlined"
            dense="dense"
            hide-details="hide-details"
          ></v-text-field>
          <v-text-field
            class="mb-3"
            label="Fim"
            type="datetime-local"
            name="showUp"
            :value="item.showUp && item.showUp.replace(' ', 'T')"
            :clearable="true"
            clear-icon="fas fa-times"
            outlined="outlined"
            dense="dense"
            hide-details="hide-details"
          ></v-text-field>
        </v-form>
        <v-btn
          class="text-none"
          @click="setRangeDateActived"
          color="secondary"
          :loading="updatingRangeDateActived"
          :disabled="updatingRangeDateActived"
          block="block"
          depressed="depressed"
          >Alterar</v-btn
        >
      </v-card>
    </v-dialog>
  </div>
</template>

<script>
import ToolbarButton from "@/components/buttons/ToolbarButton";

export default {
  props: {
    data: {
      type: Object,
      required: true,
    },
    item: {
      type: Object,
      required: true,
    },
    itemId: {
      type: String,
      required: true,
    },
  },
  data: () => ({
    rangeDateActiveDialog: false,
    updatingRangeDateActived: false,
  }),
  methods: {
    setRangeDateActived() {
      this.updatingRangeDateActived = true;

      const data = Object.fromEntries(
        new FormData(this.$refs.rangeDateActiveForm.$el).entries()
      );

      this.$rest(this.data.key)
        .put({ id: this.itemId, save: () => false, data })
        .finally(() => (this.updatingRangeDateActived = false));
    },
  },
  components: {
    ToolbarButton,
  },
};
</script>

<style>
.table-module-edit-rangedateactived input::-webkit-calendar-picker-indicator {
  filter: brightness(1) invert(1);
}
</style>
