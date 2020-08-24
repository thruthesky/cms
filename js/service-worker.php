<?php
header('Service-Worker-Allowed: /');
header('Content-Type: application/javascript');
include_once '../config.php';
$appVersion = Config::$appVersion;
?>

const cacheName = 'cache-v1';
console.log('service worker version. v2. ', (new Date).toLocaleString());
const precacheResources = [
  '/wp-content/themes/cms/pwa-start.html?v=' + '<?php echo $appVersion?>',
  '/wp-content/themes/cms/js/jquery-3.5.1-min.js?v=' + '<?php echo $appVersion?>',
'/wp-content/themes/cms/css/bootstrap-4.5.2/css/bootstrap.min.css?v=' + '<?php echo $appVersion?>',
'/wp-content/themes/cms/css/bootstrap-4.5.2/js/bootstrap.bundle.min.js?v' +  '<?php echo $appVersion?>',
'/wp-content/themes/cms/css/fontawesome/css/all.css?v=' + '<?php echo $appVersion?>',
'/wp-content/themes/cms/css/index.css?v=' + '<?php echo $appVersion?>',
'/wp-content/themes/cms/js/index.js?v=' + '<?php echo $appVersion?>',
'/wp-content/themes/cms/manifest.json?v=' + '<?php echo $appVersion?>',
'/wp-content/themes/cms/favicon.ico?v=' + '<?php echo $appVersion?>',
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
<!--  console.log('fetch: Fetch intercepted for:', event.request.url);-->
  event.respondWith(caches.match(event.request)
    .then(cachedResponse => {
        if (cachedResponse) {
          return cachedResponse;
        }
        return fetch(event.request);
      })
    );
});

