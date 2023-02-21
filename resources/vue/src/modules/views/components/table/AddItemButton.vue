<template>
  <ToolbarButton @click="add" tip="Adicionar" icon="fas fa-plus" dark="dark" />
</template>

<script>
import ToolbarButton from "../../../../components/buttons/ToolbarButton.vue";

export default {
  props: {
    moduleKey: {
      type: String,
      required: true,
    },
  },
  methods: {
    add() {
      this.$emit("loading", true);

      this.$rest(this.moduleKey)
        .post()
        .then(({ id }) => this.$router.push(this.$route.path + "/" + id))
        .finally(() => this.$emit("loading", false));
    },
  },
  components: {
    ToolbarButton,
  },
};
</script>
