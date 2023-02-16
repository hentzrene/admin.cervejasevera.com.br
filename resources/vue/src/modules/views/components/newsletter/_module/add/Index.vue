<template>
  <div></div>
</template>

<script>
export default {
  data: () => ({
    data: {
      fields: [
        {
          name: "E-mail",
          key: "email",
          type: "1",
          unique: false,
        },
      ],
      viewOptions: { listHeaders: ["email"] },
    },
  }),
  methods: {
    validate: () => true,
  },
};
</script>
