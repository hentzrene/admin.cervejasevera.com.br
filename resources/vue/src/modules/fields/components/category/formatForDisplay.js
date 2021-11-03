export default async ({ component, value, moduleId, fieldData }) => {
  const linkModule = fieldData.options.linkModule;

  let mod, getOptions;
  if (linkModule) {
    mod = linkModule;
    getOptions = { params: { fields: "id,title" } };
  } else {
    mod = "modulesCategories";
    getOptions = {
      params: {
        moduleId,
      },
      keepCache: true,
    };
  }

  let category = component.$rest(mod).list.find(({ id }) => id == value);

  if (category) return category.title;

  let r = await component.$rest(mod).get(getOptions);
  category = r.find(({ id }) => id == value);

  return category ? category.title : "";
};
