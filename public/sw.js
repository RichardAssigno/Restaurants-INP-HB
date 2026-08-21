const CACHE_NAME = 'sgi-restaurant-static-v1';
const PRECACHE_URLS = [
    '/manifest.webmanifest',
    '/pwa/icon-192.png',
    '/pwa/icon-512.png'
];

self.addEventListener('install', function (event) {
    event.waitUntil(
        caches.open(CACHE_NAME)
            .then(function (cache) {
                return cache.addAll(PRECACHE_URLS);
            })
            .then(function () {
                return self.skipWaiting();
            })
    );
});

self.addEventListener('activate', function (event) {
    event.waitUntil(
        caches.keys()
            .then(function (cacheNames) {
                return Promise.all(
                    cacheNames
                        .filter(function (cacheName) {
                            return cacheName.startsWith('sgi-restaurant-static-') && cacheName !== CACHE_NAME;
                        })
                        .map(function (cacheName) {
                            return caches.delete(cacheName);
                        })
                );
            })
            .then(function () {
                return self.clients.claim();
            })
    );
});

self.addEventListener('fetch', function (event) {
    const request = event.request;
    const url = new URL(request.url);

    if (
        request.method !== 'GET'
        || url.origin !== self.location.origin
        || request.mode === 'navigate'
        || !['style', 'script', 'image', 'font'].includes(request.destination)
    ) {
        return;
    }

    event.respondWith(
        fetch(request)
            .then(function (response) {
                if (response.ok && response.type === 'basic') {
                    const responseToCache = response.clone();
                    caches.open(CACHE_NAME).then(function (cache) {
                        cache.put(request, responseToCache);
                    });
                }

                return response;
            })
            .catch(function () {
                return caches.match(request).then(function (cachedResponse) {
                    return cachedResponse || Response.error();
                });
            })
    );
});
