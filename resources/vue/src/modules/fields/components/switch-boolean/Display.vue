<template>
  <div class="d-flex">
    <div @click.stop>
      <v-switch
        @change="update"
        v-model="value_"
        :loading="loading"
        :disabled="loading"
        color="accent"
        dense="dense"
        outlined="outlined"
        dark="dark"
      ></v-switch>
    </div>
  </div>
</template>

<script>
export default {
  props: {
    item: {
      type: Object,
    },
    name: {
      type: String,
    },
    value: {
      type: String,
    },
  },
  data: () => ({
    value_: null,
    loading: false,
  }),
  computed: {
    moduleKey() {
      return this.$route.params.module;
    },
  },
  methods: {
    update() {
      this.loading = true;

      this.$rest(this.moduleKey)
        .put({
          id: this.item.id,
          data: {
            [this.name]: this.value_ ? "1" : "0",
          },
        })
        .finally(() => {
          this.loading = false;
        });
    },
  },
  mounted() {
    this.value_ = +this.value;
  },
};
</script>
