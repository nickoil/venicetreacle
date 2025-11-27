const CACHE_NAME = 'v1_cache';
const urlsToCache = [
  '/',
  // '/install.html',

];

self.addEventListener('install', (event) => {
    self.skipWaiting();
});

self.addEventListener('fetch', (event) => {
    if (event.request.headers.get('range')) {
        // Skip caching for partial requests
        return;
    }
    
    event.respondWith(
        fetch(event.request)
            .then((response) => {
                // Optionally update the cache with the fresh response
                const clone = response.clone();
                caches.open('my-cache').then((cache) => {
                    if (response.ok && response.status === 200) {
                        cache.put(event.request, clone);
                    }
                });
                return response;
            })
            .catch(() => caches.match(event.request)) // Fallback to cache if network fails
    );
});

self.addEventListener('activate', (event) => {
    event.waitUntil(clients.claim());
});