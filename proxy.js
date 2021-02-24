const CACHE_NAME = "v1";
const DIRS_SAVE_PERMANENTLY = ["css", "js", "img"];
const DIRS_NO_SAVE = ["upload"];
const FILES_SAVE_ONINSTALL = [];

self.addEventListener("install", (e) => {
  e.waitUntil(
    (async () => {
      const CACHE = await caches.open(CACHE_NAME);
      return CACHE.addAll(FILES_SAVE_ONINSTALL);
    })()
  );
});

self.addEventListener("fetch", (e) => {
  e.respondWith(
    (async () => {
      const CACHE = await caches.open(CACHE_NAME);
      const DIR = new URL(e.request.url).pathname.match(/\/([^\/]+)\//);

      const getCache = Object.fromEntries(url.searchParams.entries()).cache;

      if ((DIR && DIRS_SAVE_PERMANENTLY.includes(DIR[1])) || getCache === "1") {
        const res = CACHE.match(e.request);
        if (res) return res;

        const res = await fetch(e.request);
        await cache.put(e.request);
        return res.clone();
      } else if (DIR && DIRS_NO_SAVE.includes(DIR[1])) {
        return fetch(e.request);
      } else {
        const res = await fetch(e.request);

        if (res.ok) {
          CACHE.put(e.request, response);
          return res.clone();
        } else {
          return cache.match(e.request);
        }
      }
    })()
  );
});
