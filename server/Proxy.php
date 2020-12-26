<?php

// use Model\Content;

header('Content-Type: application/javascript');

// $slidesMobile = Content::getAll(
//   ['foto' => 'img'],
//   Content::SLIDES['mobile']
// );

// $slidesMobile = array_map(function ($s) {
//   return '/' . $s['img'];
// }, $slidesMobile);

// $slidesDesktop = Content::getAll(
//   ['foto' => 'img'],
//   Content::SLIDES['desktop']
// );

// $slidesDesktop = array_map(function ($s) {
//   return '/' . $s['img'];
// }, $slidesDesktop);

$items = [
  "/img/bg.png",
  "/img/logo-construtora.png",
  "/img/logo-construtora-short.png",
  "/img/logo-construtora-white.png",
  "/img/logo-icf.png",
  "/img/logo-icf-short.png",
  "/img/logo-icf-white.png",
  "/",
  "/construtora",
  "/construtora/quem-somos",
  "/construtora/servicos",
  "/construtora/galerias-de-obras",
  "/construtora/fale-conosco",
  "/construtora/trabalhe-conosco",
  "/construtora/novidades",
  "/construtora/videos",
  "/icf",
  "/icf/quem-somos",
  "/icf/servicos",
  "/icf/galerias-de-obras",
  "/icf/fale-conosco",
  "/icf/trabalhe-conosco",
  "/icf/novidades",
  "/icf/videos",
  "/img/banner.png",
];

// $items = array_merge($items, $slidesMobile, $slidesDesktop);
?>

self.addEventListener("install", function (event) {
event.waitUntil(
caches.open("v2").then(function (cache) {
return cache.addAll(JSON.parse(`<?= json_encode($items) ?>`));
})
);
});

self.addEventListener("fetch", (e) => {
e.respondWith(
(async () => {
const dir = new URL(e.request.url).pathname.match(/\/([^/]+)\//);

if (
(dir && ["css", "js", "img"].includes(dir[1])) ||
/.*\?cache$/.test(e.request.url)
) {
return caches.open("v2").then((cache) =>
cache.match(e.request).then(
(response) =>
response ||
fetch(e.request).then((response) => {
if (response.ok) cache.add(e.request);
return response;
})
)
);
} else if (dir && ["admin", "upload"].includes(dir[1])) {
return fetch(e.request);
} else {
return fetch(e.request).then(
(response) => {
if (response.ok)
caches.open("v1").then((cache) => cache.put(e.request, response));

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
})()
);
});