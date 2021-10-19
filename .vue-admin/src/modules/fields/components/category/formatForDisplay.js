export default async ({ component, value, moduleId }) => {
  let category = component
    .$rest("modulesCategories")
    .list.find(({ id }) => id == value);

  if (category) category.title;

  return component
    .$rest("modulesCategories")
    .get({
      params: {
        moduleId,
      },
      keepCache: true,
    })
    .then((r) => {
      const category = r.find(({ id }) => id == value);
      return category ? category.title : "";
    });
};
