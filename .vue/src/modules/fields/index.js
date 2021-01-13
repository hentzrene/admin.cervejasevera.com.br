export default Object.fromEntries(
  require
    .context("./components", true, /\.vue$/)
    .keys()
    .filter((path) => /\.\/[^]+\/Index.vue/.test(path))
    .map((path) => {
      return [
        path
          .replace(/\.\/([^./]+)\/Index.vue/, "$1")
          .toLowerCase()
          .replace(/[^a-z]/g, ""),
        () => import("./components/" + path.slice(2)),
      ];
    })
);
