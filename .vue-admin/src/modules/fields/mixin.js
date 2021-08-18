export default {
  props: {
    label: {
      type: String,
      required: true,
    },
    value: String,
    name: {
      type: String,
      required: true,
    },
  },
  computed: {
    isAdminUser() {
      return this.$store.state.user.type == 1;
    },
  },
};
