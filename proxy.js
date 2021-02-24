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
      const request = e.request;

      if (request.method !== "GET") return fetch(request);

      const CACHE = await caches.open(CACHE_NAME);
      const url = new URL(request.url);
      const DIR = url.pathname.match(/\/([^\/]+)\//);
      const getCache = Object.fromEntries(url.searchParams.entries()).cache;

      if ((DIR && DIRS_SAVE_PERMANENTLY.includes(DIR[1])) || getCache === "1") {
        let response = await CACHE.match(request);
        if (response) return response;

        response = await fetch(request);
        CACHE.put(request, response.clone());
        return response;
      } else if (DIR && DIRS_NO_SAVE.includes(DIR[1])) {
        return fetch(request);
      } else {
        let response = await fetch(request);

        if (response.ok) {
          CACHE.put(request, response.clone());
          return response;
        } else {
          return (await CACHE.match(request)) || response;
        }
      }
    })()
  );
});
