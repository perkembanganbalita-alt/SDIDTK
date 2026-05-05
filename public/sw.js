const CACHE_NAME = 'sdidtk-pwa-v2';
const STATIC_ASSETS = [
    '/offline.html',
    '/manifest.json',
    '/images/baby_illustration.png',
];

// Install event: Pre-cache offline page and essential assets
self.addEventListener('install', event => {
    event.waitUntil(
        caches.open(CACHE_NAME).then(cache => {
            console.log('[ServiceWorker] Pre-caching offline assets');
            return cache.addAll(STATIC_ASSETS);
        })
    );
    self.skipWaiting(); // Force the waiting service worker to become the active service worker
});

// Activate event: Clean up old caches
self.addEventListener('activate', event => {
    event.waitUntil(
        caches.keys().then(cacheNames => {
            return Promise.all(
                cacheNames.filter(name => {
                    return name !== CACHE_NAME;
                }).map(name => {
                    console.log('[ServiceWorker] Deleting old cache:', name);
                    return caches.delete(name);
                })
            );
        })
    );
    self.clients.claim(); // Claim control of all clients immediately
});

// Fetch event: Intercept requests and apply caching strategies
self.addEventListener('fetch', event => {
    const request = event.request;
    
    // 1. HTML Requests (Navigating to pages) -> Network First, Fallback to offline.html
    if (request.mode === 'navigate' || (request.method === 'GET' && request.headers.get('accept').includes('text/html'))) {
        event.respondWith(
            fetch(request).catch(() => {
                return caches.match('/offline.html');
            })
        );
        return;
    }

    // 2. Static Assets (CSS, JS, Images) -> Cache First, Fallback to Network
    if (request.destination === 'style' || request.destination === 'script' || request.destination === 'image') {
        event.respondWith(
            caches.match(request).then(cachedResponse => {
                if (cachedResponse) {
                    return cachedResponse; // Return from cache if found
                }
                
                // If not in cache, fetch from network and cache it for next time
                return fetch(request).then(networkResponse => {
                    // Don't cache if not a valid response or if it's an opaque response (e.g. from third party)
                    if (!networkResponse || networkResponse.status !== 200 || networkResponse.type !== 'basic') {
                        return networkResponse;
                    }
                    
                    const responseToCache = networkResponse.clone();
                    
                    caches.open(CACHE_NAME).then(cache => {
                        if (request.method === 'GET') {
                            cache.put(request, responseToCache);
                        }
                    });
                    
                    return networkResponse;
                });
            }).catch(() => {
                // If both cache and network fail, we can silently fail or return a fallback image
            })
        );
        return;
    }

    // 3. API Requests, form submissions, etc -> Network Only
    event.respondWith(
        fetch(request).catch(() => {
            // Can't do much here if API fails offline, app will handle JS errors
        })
    );
});
