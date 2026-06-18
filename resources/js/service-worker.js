import { clientsClaim, skipWaiting } from 'workbox-core';
import { precacheAndRoute } from 'workbox-precaching';
import { registerRoute, NavigationRoute } from 'workbox-routing';
import { CacheFirst, NetworkFirst, StaleWhileRevalidate, NetworkOnly } from 'workbox-strategies';
import { CacheableResponsePlugin } from 'workbox-cacheable-response';
import { ExpirationPlugin } from 'workbox-expiration';

// Make the updated worker control the current tab as soon as it activates.
skipWaiting();
clientsClaim();

// 1. Precaching
// Workbox will replace self.__WB_MANIFEST with the generated list of files to precache.
precacheAndRoute(self.__WB_MANIFEST || []);

// 2. Offline Fallback for Navigation
const OFFLINE_URL = '/offline';
const CACHE_NAME_OFFLINE = 'offline-fallback-v1';

const offlineFallback = async () => {
    const cache = await caches.open(CACHE_NAME_OFFLINE);

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

const htmlNetworkFallback = async ({ request }) => {
    try {
        return await fetch(request);
    } catch (error) {
        return offlineFallback();
    }
};

const isSameOriginHtmlRequest = ({ request, url }) => {
    return request.method === 'GET'
        && url.origin === self.location.origin
        && request.headers.get('accept')?.includes('text/html')
        && ! url.pathname.startsWith('/livewire/');
};

self.addEventListener('install', (event) => {
    event.waitUntil(
        caches.open(CACHE_NAME_OFFLINE).then((cache) => cache.add(OFFLINE_URL))
    );
});

// Clean up old caches on activate
self.addEventListener('activate', (event) => {
    event.waitUntil(
        caches.keys().then((cacheNames) => {
            return Promise.all(
                cacheNames.map((cacheName) => {
                    if (cacheName !== CACHE_NAME_OFFLINE && !cacheName.startsWith('workbox-precache')) {
                        return caches.delete(cacheName);
                    }
                })
            );
        })
    );
});

// Register navigation route with NetworkFirst and offline fallback
const navigationHandler = new NetworkFirst({
    cacheName: 'navigations',
    plugins: [
        new CacheableResponsePlugin({
            statuses: [200],
        }),
    ],
});

registerRoute(
    new NavigationRoute(async (params) => {
        try {
            return await navigationHandler.handle(params);
        } catch (error) {
            return offlineFallback();
        }
    })
);

// Livewire wire:navigate prefetches pages as normal HTML fetches, not browser navigations.
registerRoute(isSameOriginHtmlRequest, htmlNetworkFallback);

// 3. Images: Stale While Revalidate
registerRoute(
    ({ request }) => request.destination === 'image',
    new StaleWhileRevalidate({
        cacheName: 'images',
        plugins: [
            new CacheableResponsePlugin({
                statuses: [0, 200],
            }),
            new ExpirationPlugin({
                maxEntries: 60,
                maxAgeSeconds: 30 * 24 * 60 * 60, // 30 Days
            }),
        ],
    })
);

// 4. Livewire and Auth: Network Only
registerRoute(
    ({ url }) => {
        return url.pathname.startsWith('/livewire/') || 
               url.pathname.startsWith('/login') || 
               url.pathname.startsWith('/logout') || 
               url.pathname.startsWith('/register');
    },
    new NetworkOnly()
);

// 5. API / Cors requests: Network First
registerRoute(
    ({ request, url }) => {
        return url.pathname.startsWith('/api/')
            || (request.mode === 'cors' && url.origin !== self.location.origin);
    },
    new NetworkFirst({
        cacheName: 'api-cache',
        networkTimeoutSeconds: 5,
        plugins: [
            new CacheableResponsePlugin({
                statuses: [200],
            }),
        ],
    })
);

// 6. Fonts & Google Fonts CDN: Cache First
registerRoute(
    ({ request, url }) => {
        return request.destination === 'font' || 
               url.host === 'fonts.googleapis.com' || 
               url.host === 'fonts.gstatic.com';
    },
    new CacheFirst({
        cacheName: 'fonts-and-assets',
        plugins: [
            new CacheableResponsePlugin({
                statuses: [0, 200],
            }),
            new ExpirationPlugin({
                maxEntries: 30,
                maxAgeSeconds: 365 * 24 * 60 * 60, // 1 Year
            }),
        ],
    })
);

// 7. Messaging Listener
self.addEventListener('message', (event) => {
    if (event.data && event.data.type === 'SKIP_WAITING') {
        self.skipWaiting();
    }
    if (event.data && event.data.type === 'CLEAR_CACHES') {
        event.waitUntil(
            caches.keys().then((cacheNames) => {
                return Promise.all(
                    cacheNames.map((cacheName) => {
                        return caches.delete(cacheName);
                    })
                ).then(() => {
                    return caches.open(CACHE_NAME_OFFLINE).then((cache) => cache.add(OFFLINE_URL));
                });
            })
        );
    }
});
