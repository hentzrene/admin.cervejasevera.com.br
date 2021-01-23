<?php


header('Content-Type: application/javascript');
?>

self.addEventListener('fetch', e => {
e.respondWith(
(async () => {
dir = new URL(e.request.url).pathname.match(/\/([^\/]+)\//)

if(dir && ['css', 'js', 'img'].includes(dir[1]) || /.*\?cache$/.test(e.request.url)) {
return caches.open('v1')
.then(cache => cache.match(e.request)
.then(response => response || fetch(e.request)
.then(response => {
if(response.ok) cache.add(e.request)
return response;
})
)
)
}
else if(dir && ['upload'].includes(dir[1])) {
return fetch(e.request)
} else {
return fetch(e.request)
.then(
response => {
if(response.ok)
caches.open('v1')
.then(cache => cache.put(e.request, response))

return response.clone();
},
() => caches.open('v1')
.then(
cache => cache.match(e.request)
.then(response => response)
)
)
}
})()
);
});