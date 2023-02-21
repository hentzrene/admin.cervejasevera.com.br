<template>
  <div class="d-flex justify-end">
    <v-btn
      @click.stop="toggleActive(item.id, !itemActive)"
      :color="color"
      icon
      small
    >
      <v-icon small>{{ icon }}</v-icon>
    </v-btn>
  </div>
</template>

<script>
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
    item: {
      type: Object,
      required: true,
    },
  },
  computed: {
    itemActive() {
      return +this.item.active;
    },
    color() {
      return this.itemActive ? "green" : "blue";
    },
    icon() {
      return this.itemActive ? "fas fa-eye" : "fas fa-eye-slash";
    },
  },
  methods: {
    toggleActive(id, active) {
      this.$rest(this.moduleKey)
        .put({
          id,
          prop: "active",
          data: { value: active | 0 },
        })
        .then(
          () => (this.items.find((item) => item.id == id).active = active | 0)
        );
    },
  },
};
</script>
