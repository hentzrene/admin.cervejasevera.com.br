export default Object.fromEntries(
  require
    .context("./components", true, /\.vue$/)
    .keys()
    .filter(path => /\.\/[^]+\/Index.vue/.test(path))
    .map(path => [
      path
        .replace(/\.\/([^./]+)\/Index.vue/, "$1")
        .toLowerCase()
        .replace(/[^a-z]/g, "")
        .concat("field"),
      () => import("./components/" + path.slice(2))
    ])
);
