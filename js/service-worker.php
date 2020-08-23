<?php
header('Service-Worker-Allowed: /');
header('Content-Type: application/javascript');
?>

const cacheName = 'cache-v1';
console.log('service worker version. v2. ', (new Date).toLocaleString());
const precacheResources = [
  '/wp-content/themes/cms/pwa-start.html',
  '/wp-content/themes/cms/js/jquery-3.5.1-min.js',
'/wp-content/themes/cms/css/bootstrap-5-alpha-0.min.css',
'/wp-content/themes/cms/css/fontawesome/css/all.css',
'/wp-content/themes/cms/css/index.css',
'/wp-content/themes/cms/js/index.js',
'/wp-content/themes/cms/manifest.json',
'/wp-content/themes/cms/favicon.ico',
];

self.addEventListener('install', event => {
  console.log('install');
  event.waitUntil(
    caches.open(cacheName)
      .then(cache => {
          console.log('cache.addAll', precacheResources);
        return cache.addAll(precacheResources);
      })
  );
});

self.addEventListener('activate', event => {
  console.log('activate');
});

self.addEventListener('fetch', event => {
  console.log('fetch: Fetch intercepted for:', event.request.url);
  event.respondWith(caches.match(event.request)
    .then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request);
      })
    );
});

