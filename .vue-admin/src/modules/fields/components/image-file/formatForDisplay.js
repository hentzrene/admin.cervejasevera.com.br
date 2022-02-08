import Vue from "vue";

export default ({ component, value, id }) => {
  if (!value) return;

  const r = Vue.observable({ innerHTML: null });

  component.$rest("modulesImages").get({
    id: `${id}/${value}`,
    save: (state, { path }) => {
      const src =
        component.files.replace("/admin", "") + path + "?resize=1&w=106";

      r.innerHTML = `<img style="height: 60px; width: 106px; object-fit: contain; margin-top: 6px; border-radius: 4px;" src="${src}">`;
    },
  });

  return r;
};
