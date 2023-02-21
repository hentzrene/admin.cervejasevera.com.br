import Index from "./Index.vue";

export default {
  name: "tagsfield",
  component: Index,
  formatForDisplay: async ({ component, value, moduleId }) => {
    let tag = component.$rest("modulesTags").list.find(({ id }) => id == value);

    if (tag) return tag.title;

    return component
      .$rest("modulesTags")
      .get({
        params: {
          moduleId,
        },
        keepCache: true,
      })
      .then((r) => {
        const tag = r.find(({ id }) => id == value);
        return tag ? tag.title : "";
      });
  },
};
