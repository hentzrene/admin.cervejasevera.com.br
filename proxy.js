self.addEventListener("fetch", (e) => {
  e.respondWith(
    (async () => {
      const url = new URL(e.request.url);

      if (e.request.method === "GET" && url.host === "www.mrxweb.com.br") {
        const dir = url.pathname.match(/\/([^\/]+)\//);

        if (
          (dir && ["css", "js", "img"].includes(dir[1])) ||
          /.*\?cache$/.test(e.request.url)
        ) {
          return caches.open("v1").then((cache) =>
            cache.match(e.request).then(
              (response) =>
                response ||
                fetch(e.request).then((response) => {
                  if (response.ok) cache.add(e.request);
                  return response;
                })
            )
          );
        } else {
          return fetch(e.request).then(
            (response) => {
              if (response.ok)
                caches
                  .open("v1")
                  .then((cache) => cache.put(e.request, response));

              return response.clone();
            },
            () =>
              caches
                .open("v1")
                .then((cache) =>
                  cache.match(e.request).then((response) => response)
                )
          );
        }
      } else {
        return fetch(e.request);
      }
    })()
  );
});
