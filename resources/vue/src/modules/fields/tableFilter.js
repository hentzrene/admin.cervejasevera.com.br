export default Object.fromEntries(
  require
    .context("./components", true, /\.vue$/)
    .keys()
    .filter((path) => /\.\/[^]+\/TableFilter.vue/.test(path))
    .map((path) => [
      path
        .replace(/\.\/([^./]+)\/TableFilter.vue/, "$1")
        .toLowerCase()
        .replace(/[^a-z]/g, "")
        .concat("TableFilter"),
      require("./components/" + path.slice(2)).default,
    ])
);
