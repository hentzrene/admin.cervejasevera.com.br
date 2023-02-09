<template>
  <div>
    <TemplateDialogHeaderButton
      @click="$refs.imageFileLabel.click()"
      icon="fas fa-upload"
      text="Enviar"
    />
    <label :for="'imageFileInput_' + fieldId" ref="imageFileLabel"></label>
    <input
      class="ma-0 pa-0"
      @input="upload"
      :id="'imageFileInput_' + fieldId"
      :value="file"
      ref="imageFileInput"
      type="file"
      accept="image/*"
      multiple
      hide-details
      hidden
    />
  </div>
</template>

<script>
import TemplateDialogHeaderButton from "../../../templates/DialogHeaderButton";

export default {
  props: {
    fieldId: {
      type: Number,
      required: true,
    },
    items: {
      type: Array,
      required: true,
    },
  },
  data: () => ({
    progress: 0,
    file: null,
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub || 1;
    },
  },
  methods: {
    upload({ target }) {
      this.disabled = true;

      const files = target.files;

      Array.from(files).map((file) => {
        const item = {
          loading: true,
          progress: 0,
        };

        const onUploadProgress = ({ loaded, total }) => {
          item.progress = parseInt((loaded / total) * 100);
        };

        this.items.push(item);

        return this.$rest("modulesImages")
          .upload({
            file,
            prop: "image",
            params: {
              itemId: this.itemId,
              moduleId: this.moduleId,
              fieldId: this.fieldId,
            },
            onUploadProgress,
          })
          .then(({ file, id }) => {
            item.loading = false;
            item.id = id;
            item.path = file;
          })
          .catch(() => {
            const index = this.items.indexOf(item);
            this.items.splice(index, 1);
          });
      });

      this.$refs.imageFileInput.value = "";
    },
  },
  components: {
    TemplateDialogHeaderButton,
  },
};
</script>

<style scoped>
.v-progress-circular__overlay {
  /* transition: none !important; */
  transition-duration: 0.5ms !important;
}
</style>
