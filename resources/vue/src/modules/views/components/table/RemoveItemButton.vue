<template>
  <ToolbarButton
    @click="remove"
    :disabled="disabled"
    tip="Remover"
    icon="fas fa-trash"
    dark="dark"
  />
</template>

<script>
import ToolbarButton from "../../../../components/buttons/ToolbarButton.vue";

export default {
  props: {
    moduleKey: {
      type: String,
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
    selecteds: {
      type: Array,
      required: true,
    },
    disabled: {
      type: Boolean,
      defautl: false,
    },
  },
  methods: {
    remove() {
      this.$emit("loading", true);

      Promise.all(
        this.selecteds.map(({ id }) =>
          this.$rest(this.moduleKey)
            .delete({ id })
            .then(() => {
              this.items.splice(
                this.items.findIndex((v) => v.id == id),
                1
              );
            })
        )
      ).finally(() => this.$emit("loading", false));
    },
  },
  components: {
    ToolbarButton,
  },
};
</script>
