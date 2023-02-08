<template>
  <TemplateDialogAny
    @input="(data) => $emit('input', data)"
    :value="value"
    title="Galeria"
    max-width="880px"
    ><template #actions>
      <OrderButton @loading="(val) => (loadingOrder = val)" :items="images" />
      <TemplateDialogHeaderButton
        @click="editDialog = true"
        :disabled="selecteds.length !== 1"
        icon="fas fa-pencil-alt"
        text="Editar Título"
      />
      <TemplateDialogHeaderButton
        @click="featured"
        :disabled="selecteds.length !== 1"
        icon="fas fa-star"
        text="Destacar"
      />
      <TemplateDialogHeaderButton
        @click="remove"
        :disabled="!selecteds.length"
        icon="fas fa-trash"
        text="Remover"
      />
      <UploadButton :field-id="fieldId" :items="images" />
    </template>
    <v-progress-linear
      class="mb-1"
      :indeterminate="loadingOrder"
      :class="!loadingOrder && 'opacity-0'"
      color="secondary"
    ></v-progress-linear>
    <v-item-group
      class="view-field-list-wrap d-flex flex-wrap"
      v-model="selecteds"
      v-if="images.length"
      multiple
    >
      <draggable class="view-field-list d-flex flex-wrap" v-model="images">
        <v-item
          v-slot="{ active, toggle }"
          v-for="({ path, id }, i) in images"
          :key="i"
          :value="id"
        >
          <v-img
            class="view-field-item rounded-sm"
            v-ripple
            @click="toggle"
            :class="active ? 'active cyan' : 'grey'"
            :src="files.replace(prefixPath, '') + path + '?resize=1&h=80'"
            contain
          >
            <v-overlay
              v-if="featuredImage && id === featuredImage.id"
              absolute
              opacity="0.2"
            >
              <v-icon color="yellow" size="40">fas fa-star</v-icon>
            </v-overlay>
          </v-img>
        </v-item>
      </draggable>
    </v-item-group>
    <div class="pt-8 pb-4 text-body-2 text-center font-weight-bold" v-else>
      Nenhuma imagem foi enviada.
    </div>
    <v-overlay v-model="loading">
      <Loading></Loading>
    </v-overlay>
    <Edit
      v-if="selecteds[0]"
      v-model="editDialog"
      :img="findImgById(selecteds[0])"
    ></Edit>
  </TemplateDialogAny>
</template>

<script>
import Edit from "./Edit";
import TemplateDialogAny from "../../templates/DialogAny";
import TemplateDialogHeaderButton from "../../templates/DialogHeaderButton";
import Loading from "@/components/tools/Loading";
import draggable from "vuedraggable";
import OrderButton from "./buttons/OrderButton.vue";
import UploadButton from "./buttons/UploadButton.vue";

export default {
  props: {
    value: Boolean,
    inputName: {
      type: String,
      required: true,
    },
    featuredImage: Object,
    fieldId: {
      type: Number,
      required: true,
    },
    images: {
      type: Array,
      default: () => [],
    },
  },
  data: () => ({
    loading: false,
    loadingOrder: false,
    selecteds: [],
    editDialog: false,
  }),
  computed: {
    moduleKey() {
      return this.$rest("modules").item.key;
    },
    moduleId() {
      return this.$rest("modules").item.id;
    },
    itemId() {
      return this.$route.params.sub || 1;
    },
  },
  methods: {
    remove() {
      this.loading = true;
      Promise.all(
        this.selecteds.map((id) =>
          this.$rest("modulesImages").delete({
            id: id,
            params: { moduleId: this.moduleId },
          })
        )
      ).finally(() => {
        this.selecteds = [];
        this.$parent.$parent.get().then(() => (this.loading = false));
      });
    },
    featured() {
      this.loading = true;

      const id = this.images.find(({ id }) => id == this.selecteds[0])["id"];
      const data = {};
      data[this.inputName] = id;

      this.$rest(this.moduleKey)
        .put({
          id: this.itemId,
          prop: this.inputName,
          data,
        })
        .then(() =>
          this.$rest(this.moduleKey)
            .get({ id: this.itemId })
            .then(() => {
              this.selecteds = [];
              this.loading = false;
            })
        );
    },
    findImgById(id) {
      return this.images.find((img) => img.id === id);
    },
  },
  watch: {
    editDialog(v) {
      if (!v) this.selecteds = [];
    },
  },
  components: {
    Edit,
    Loading,
    draggable,
    TemplateDialogAny,
    TemplateDialogHeaderButton,
    OrderButton,
    UploadButton,
  },
};
</script>

<style scoped>
.view-field-list {
  gap: 16px;
}
.view-field-list > div {
  height: 80px;
  width: 80px;
  max-width: 80px;
  object-fit: contain;
}
.view-field-item {
  cursor: pointer;
}
.view-field-item.active {
  outline: 2px solid cyan;
  position: relative;
}
.view-field-item.active::after {
  content: "";
  display: block;
  height: 100%;
  width: 100%;
  background: cyan;
  opacity: 0.4;
}

.opacity-0 {
  opacity: 0;
}
</style>
