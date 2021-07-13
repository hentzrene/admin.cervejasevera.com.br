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
import ClassicEditor from "@ckeditor/ckeditor5-editor-classic/src/classiceditor";
import Alignment from "@ckeditor/ckeditor5-alignment/src/alignment.js";
import Autoformat from "@ckeditor/ckeditor5-autoformat/src/autoformat.js";
import AutoImage from "@ckeditor/ckeditor5-image/src/autoimage.js";
import AutoLink from "@ckeditor/ckeditor5-link/src/autolink.js";
import BlockQuote from "@ckeditor/ckeditor5-block-quote/src/blockquote.js";
import Bold from "@ckeditor/ckeditor5-basic-styles/src/bold.js";
import Code from "@ckeditor/ckeditor5-basic-styles/src/code.js";
import Essentials from "@ckeditor/ckeditor5-essentials/src/essentials.js";
import FindAndReplace from "@ckeditor/ckeditor5-find-and-replace/src/findandreplace.js";
import FontBackgroundColor from "@ckeditor/ckeditor5-font/src/fontbackgroundcolor.js";
import FontColor from "@ckeditor/ckeditor5-font/src/fontcolor.js";
import FontSize from "@ckeditor/ckeditor5-font/src/fontsize.js";
import GeneralHtmlSupport from "@ckeditor/ckeditor5-html-support/src/generalhtmlsupport.js";
import Heading from "@ckeditor/ckeditor5-heading/src/heading.js";
import HorizontalLine from "@ckeditor/ckeditor5-horizontal-line/src/horizontalline.js";
import HtmlEmbed from "@ckeditor/ckeditor5-html-embed/src/htmlembed.js";
import Image from "@ckeditor/ckeditor5-image/src/image.js";
import ImageCaption from "@ckeditor/ckeditor5-image/src/imagecaption.js";
import ImageInsert from "@ckeditor/ckeditor5-image/src/imageinsert.js";
import ImageResize from "@ckeditor/ckeditor5-image/src/imageresize.js";
import ImageStyle from "@ckeditor/ckeditor5-image/src/imagestyle.js";
import ImageToolbar from "@ckeditor/ckeditor5-image/src/imagetoolbar.js";
import ImageUpload from "@ckeditor/ckeditor5-image/src/imageupload.js";
import Indent from "@ckeditor/ckeditor5-indent/src/indent.js";
import IndentBlock from "@ckeditor/ckeditor5-indent/src/indentblock.js";
import Italic from "@ckeditor/ckeditor5-basic-styles/src/italic.js";
import Link from "@ckeditor/ckeditor5-link/src/link.js";
import LinkImage from "@ckeditor/ckeditor5-link/src/linkimage.js";
import List from "@ckeditor/ckeditor5-list/src/list.js";
import ListStyle from "@ckeditor/ckeditor5-list/src/liststyle.js";
import MediaEmbed from "@ckeditor/ckeditor5-media-embed/src/mediaembed.js";
import MediaEmbedToolbar from "@ckeditor/ckeditor5-media-embed/src/mediaembedtoolbar.js";
import Paragraph from "@ckeditor/ckeditor5-paragraph/src/paragraph.js";
import PasteFromOffice from "@ckeditor/ckeditor5-paste-from-office/src/pastefromoffice.js";
import SourceEditing from "@ckeditor/ckeditor5-source-editing/src/sourceediting.js";
import Table from "@ckeditor/ckeditor5-table/src/table.js";
import TableCaption from "@ckeditor/ckeditor5-table/src/tablecaption.js";
import TableCellProperties from "@ckeditor/ckeditor5-table/src/tablecellproperties";
import TableProperties from "@ckeditor/ckeditor5-table/src/tableproperties";
import TableToolbar from "@ckeditor/ckeditor5-table/src/tabletoolbar.js";
import TextTransformation from "@ckeditor/ckeditor5-typing/src/texttransformation.js";
import Underline from "@ckeditor/ckeditor5-basic-styles/src/underline.js";
import FileRepository from "@ckeditor/ckeditor5-upload/src/filerepository";
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
    editor: ClassicEditor,
    editorData: "",
  }),
  computed: {
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub;
    },
    config() {
      return {
        extraPlugins: [this.upload],
        plugins: [
          Alignment,
          Autoformat,
          AutoImage,
          AutoLink,
          BlockQuote,
          Bold,
          Code,
          Essentials,
          FindAndReplace,
          FontBackgroundColor,
          FontColor,
          FontSize,
          GeneralHtmlSupport,
          Heading,
          HorizontalLine,
          HtmlEmbed,
          Image,
          ImageCaption,
          ImageInsert,
          ImageResize,
          ImageStyle,
          ImageToolbar,
          ImageUpload,
          Indent,
          IndentBlock,
          Italic,
          Link,
          LinkImage,
          List,
          ListStyle,
          MediaEmbed,
          MediaEmbedToolbar,
          Paragraph,
          PasteFromOffice,
          SourceEditing,
          Table,
          TableCaption,
          TableCellProperties,
          TableProperties,
          TableToolbar,
          TextTransformation,
          Underline,
          FileRepository,
        ],
        toolbar: {
          items: [
            "heading",
            "|",
            "alignment",
            "|",
            "bold",
            "italic",
            "underline",
            "|",
            "fontSize",
            "fontColor",
            "fontBackgroundColor",
            "|",
            "link",
            "bulletedList",
            "numberedList",
            "|",
            "outdent",
            "indent",
            "|",
            "imageInsert",
            "mediaEmbed",
            "|",
            "insertTable",
            "blockQuote",
            "code",
            "horizontalLine",
            "|",
            "htmlEmbed",
            "sourceEditing",
            "|",
            "undo",
            "redo",
            "|",
            "findAndReplace",
          ],
        },
        image: {
          toolbar: [
            "imageTextAlternative",
            "imageStyle:inline",
            "imageStyle:block",
            "imageStyle:side",
            "linkImage",
          ],
        },
        table: {
          contentToolbar: [
            "tableColumn",
            "tableRow",
            "mergeTableCells",
            "tableCellProperties",
            "tableProperties",
          ],
        },
        htmlEmbed: {
          showPreviews: true,
        },
      };
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
                .then(({ file }) => ({
                  default: Vue.files.replace("/admin", "") + file,
                }))
                .catch(() => {
                  throw "Não foi possível fazer upload do arquivo.";
                })
            ),
        });
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
  border-radius: 5px 5px 0px 0px !important;
}
.ck-source-editing-area {
  background: white;
}
.ck.ck-editor__main {
  max-height: calc(100vh - 200px);
  overflow-y: scroll;
}
</style>
