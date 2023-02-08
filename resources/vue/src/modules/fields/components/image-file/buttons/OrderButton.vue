<template>
  <TemplateDialogHeaderButton
    @click="order"
    icon="fas fa-save"
    text="Salvar Ordem"
  />
</template>

<script>
import TemplateDialogHeaderButton from "../../../templates/DialogHeaderButton";

export default {
  props: {
    items: {
      type: Array,
      required: true,
    },
  },
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
  },
  methods: {
    order() {
      this.$emit("loading", true);

      const ordered = this.items.map(({ id, path }, i) => ({
        id,
        path,
        order: i,
      }));

      this.$rest("modulesImages")
        .put({
          id: "order",
          data: { images: ordered },
          params: {
            moduleId: this.moduleId,
          },
        })
        .then(() => this.$emit("loading", false));
    },
  },
  components: {
    TemplateDialogHeaderButton,
  },
};
</script>
