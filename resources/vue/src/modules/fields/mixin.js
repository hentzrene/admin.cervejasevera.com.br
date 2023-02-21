export default {
  props: {
    name: {
      type: String,
      required: true,
    },
    label: {
      type: String,
      required: true,
    },
    value: String,
  },
  computed: {
    isAdminUser() {
      return this.$store.state.user.type == 1;
    },
  },
};
