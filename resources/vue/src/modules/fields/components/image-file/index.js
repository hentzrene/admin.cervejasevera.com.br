import Vue from "vue";
import Index from "./Index.vue";

export default {
  name: "imagefilefield",
  component: Index,
  formatForDisplay: ({ component, value, id }) => {
    if (!value) return;

    const r = Vue.observable({ innerHTML: null });

    component.$rest("modulesImages").get({
      id: `${id}/${value}`,
      save: (state, { path }) => {
        const files = component.files.replace(component.prefixPath, "");
        const src = files + path + "?resize=1&w=106";

        r.innerHTML = `<img style="height: 60px; width: 106px; object-fit: cover; margin-top: 6px; border-radius: 4px;" src="${src}">`;
      },
    });

    return r;
  },
};
