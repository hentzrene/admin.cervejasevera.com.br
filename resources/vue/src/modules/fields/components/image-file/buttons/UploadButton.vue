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
    <v-overlay v-model="uploading">
      <v-progress-circular
        class="font-weight-bold accent--text"
        :value="progress"
        :size="120"
        :rotate="-90"
        :width="12"
        color="secondary"
        >{{ progress }}%</v-progress-circular
      >
    </v-overlay>
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
    uploading: false,
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
    async upload({ target }) {
      const files = target.files;
      const onUploadProgress = ({ loaded, total }) => {
        this.progress = parseInt((loaded / total) * 100);
      };

      this.uploading = true;

      Promise.all(
        Array.from(files).map((file) =>
          this.$rest("modulesImages")
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
              this.progress = 0;
              this.items.push({ path: file, id });
            })
        )
      ).then(() => {
        this.$refs.imageFileInput.value = "";
        this.uploading = false;
      });
    },
  },
  components: {
    TemplateDialogHeaderButton,
  },
};
</script>

<style scoped>
.v-progress-circular__overlay {
  transition: none !important;
}
</style>
