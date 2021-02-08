const keys = require
  .context("./components", true, /\.js$/)
  .keys()
  .filter(path => /\.\/[^]+\/formatForDisplay.js/.test(path));

const names = keys.map(path =>
  path
    .replace(/\.\/([^./]+)\/formatForDisplay.js/, "$1")
    .toLowerCase()
    .replace(/[^a-z]/g, "")
);

const imports = keys.map(path =>
  import("./components/" + path.slice(2)).then(m => m.default)
);

export default Promise.all(imports).then(importsResolved =>
  importsResolved.map((imp, i) => [names[i], imp])
);
