<template lang="pug">
grid-item(col-end=2)
  .grey--text.text--lighten-1.font-weight-bold.text-caption {{ label }}
  div(:class="[disabled ? 'disabled' : '', 'view-big-text-field']")
    ckeditor.elevation-1(
      @input="(data) => (editorData = data)",
      :value="value",
      :editor="editor",
      :config="config",
      :disabled="disabled"
    )
    v-text-field(v-model="editorData", :name="name", type="hidden")
</template>

<script>
import Editor from "@ckeditor/ckeditor5-build-classic";
import mixin from "../../mixin";

export default {
  props: {
    disabled: {
      type: Boolean,
      default: false,
    },
    fieldId: {
      type: Number,
      required: true,
    },
  },
  data: () => ({
    editor: Editor,
    editorData: "",
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub;
    },
    upload() {
      const Vue = this;

      return function (e) {
        e.plugins.get("FileRepository").createUploadAdapter = (loader) => ({
          loader,
          upload: () =>
            loader.file.then((file) =>
              Vue.$rest("modulesImages")
                .upload({
                  file,
                  prop: "image",
                  params: {
                    itemId: Vue.itemId,
                    moduleId: Vue.moduleId,
                    fieldId: Vue.fieldId,
                  },
                })
                .catch(() => {
                  throw "Não foi possível fazer upload do arquivo.";
                })
            ),
        });
      };
    },
    config() {
      return {
        extraPlugins: [this.upload],
      };
    },
  },
  mounted() {
    this.editorData = this.value;
  },
  mixins: [mixin],
};
</script>

<style>
.view-big-text-field {
  position: relative;
  color: black !important;
}
.view-big-text-field > div {
  height: 100%;
  min-height: "auto";
}
.view-big-text-field.disabled {
  position: relative;
  color: #00000090 !important;
}
.ck.ck-content {
  min-height: 200px !important;
  border-radius: 0px 0px 5px 5px !important;
}
.ck.ck-toolbar.ck-toolbar_grouping {
  background: black;
  border-radius: 5px 5px 0px 0px !important;
  border: 1px solid #333333 !important;
}
.ck.ck-toolbar__items {
  filter: invert(1);
}
</style>
