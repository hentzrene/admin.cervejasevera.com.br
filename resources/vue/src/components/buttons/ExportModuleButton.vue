<template>
  <div>
    <toolbar-button
      @click="exportation"
      tip="Exportar"
      icon="fas fa-file-export"
      dark="dark"
    ></toolbar-button
    ><a ref="link" download="export"></a>
  </div>
</template>

<script>
import ToolbarButton from "./ToolbarButton";
import { required } from "../forms/rules";

export default {
  props: {
    moduleId: {
      type: String,
      required: true,
    },
  },
  data: () => ({
    dialog: false,
    rules: {
      required,
    },
    loading: false,
  }),
  components: {
    ToolbarButton,
  },
  methods: {
    async exportation() {
      this.loading = true;

      const body = {
        moduleId: this.moduleId,
      };

      const r = await fetch(
        `${this.server}/rest/modules/${this.moduleId}/export`,
        {
          method: "POST",
          body: JSON.stringify(body),
          headers: {
            Authorization: localStorage.getItem(`token`),
          },
        }
      );
      this.loading = false;

      if (!r.ok) {
        const data = await r.json();
        this.$store.dispatch("endRequest", data);
      } else {
        const blob = await r.blob();

        const objectURL = URL.createObjectURL(blob);

        const a = this.$refs.link;
        a.href = objectURL;
        a.click();
        a.href = "";

        URL.revokeObjectURL(objectURL);
      }
    },
  },
};
</script>
