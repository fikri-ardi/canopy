/* Wrapper worker that immediately claims the current page.
 * The existing Workbox worker stays responsible for caching logic.
 */
self.addEventListener('install', () => {
    self.skipWaiting();
});

self.addEventListener('activate', (event) => {
    event.waitUntil(self.clients.claim());
});

importScripts('/service-worker.js');
