const CACHE_VERSION = 'v2';
const OFFLINE_URL = '/offline';
const OFFLINE_CACHE = `offline-fallback-${CACHE_VERSION}`;
const ASSET_CACHE = `app-assets-${CACHE_VERSION}`;
const IMAGE_CACHE = `images-${CACHE_VERSION}`;
const FONT_CACHE = `fonts-and-assets-${CACHE_VERSION}`;

const CORE_ASSETS = [
    OFFLINE_URL,
    '/manifest.webmanifest',
    '/images/favicon.svg',
    '/images/icons/icon-192x192.png',
    '/images/icons/icon-192x192-maskable.png',
    '/images/icons/icon-512x512.png',
    '/images/icons/icon-512x512-maskable.png',
];

self.addEventListener('install', (event) => {
    self.skipWaiting();

    event.waitUntil(
        caches.open(OFFLINE_CACHE).then((cache) => cache.addAll(CORE_ASSETS))
    );
});

self.addEventListener('activate', (event) => {
    const currentCaches = new Set([OFFLINE_CACHE, ASSET_CACHE, IMAGE_CACHE, FONT_CACHE]);

    event.waitUntil(
        caches.keys()
            .then((cacheNames) => Promise.all(
                cacheNames
                    .filter((cacheName) => ! currentCaches.has(cacheName))
                    .map((cacheName) => caches.delete(cacheName))
            ))
            .then(() => self.clients.claim())
    );
});

const offlineFallback = async () => {
    const cache = await caches.open(OFFLINE_CACHE);

    const response = await cache.match(OFFLINE_URL);

    if (! response) {
        return Response.error();
    }

    return new Response(await response.text(), {
        status: 200,
        statusText: 'OK',
        headers: {
            'Content-Type': 'text/html; charset=UTF-8',
            'Cache-Control': 'no-store',
        },
    });
};

const fetchAndCache = async (request, cacheName) => {
    const response = await fetch(request);

    if (response && (response.ok || response.type === 'opaque')) {
        const cache = await caches.open(cacheName);
        await cache.put(request, response.clone());
    }

    return response;
};

const staleWhileRevalidate = async (request, cacheName) => {
    const cachedResponse = await caches.match(request, { cacheName });
    const networkResponse = fetchAndCache(request, cacheName).catch(() => null);

    return cachedResponse || await networkResponse || Response.error();
};

const cacheFirst = async (request, cacheName) => {
    const cachedResponse = await caches.match(request, { cacheName });

    return cachedResponse || fetchAndCache(request, cacheName);
};

const isSameOriginHtmlRequest = (request, url) => {
    return request.method === 'GET'
        && url.origin === self.location.origin
        && (request.mode === 'navigate' || request.headers.get('accept')?.includes('text/html'))
        && ! url.pathname.startsWith('/livewire/');
};

self.addEventListener('fetch', (event) => {
    const { request } = event;
    const url = new URL(request.url);

    if (request.method !== 'GET') {
        return;
    }

    if (
        url.pathname.startsWith('/livewire/')
        || url.pathname.startsWith('/login')
        || url.pathname.startsWith('/logout')
        || url.pathname.startsWith('/register')
    ) {
        event.respondWith(fetch(request));
        return;
    }

    if (isSameOriginHtmlRequest(request, url)) {
        event.respondWith(fetch(request).catch(() => offlineFallback()));
        return;
    }

    if (url.pathname.startsWith('/api/')) {
        event.respondWith(fetch(request));
        return;
    }

    if (request.destination === 'image') {
        event.respondWith(staleWhileRevalidate(request, IMAGE_CACHE));
        return;
    }

    if (
        request.destination === 'style'
        || request.destination === 'script'
        || url.pathname.startsWith('/build/')
    ) {
        event.respondWith(cacheFirst(request, ASSET_CACHE));
        return;
    }

    if (
        request.destination === 'font'
        || url.hostname === 'fonts.googleapis.com'
        || url.hostname === 'fonts.gstatic.com'
    ) {
        event.respondWith(cacheFirst(request, FONT_CACHE));
    }
});

self.addEventListener('message', (event) => {
    if (event.data?.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }

    if (event.data?.type === 'CLEAR_CACHES') {
        event.waitUntil(
            caches.keys()
                .then((cacheNames) => Promise.all(cacheNames.map((cacheName) => caches.delete(cacheName))))
                .then(() => caches.open(OFFLINE_CACHE))
                .then((cache) => cache.addAll(CORE_ASSETS))
        );
    }
});
