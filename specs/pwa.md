Convert Alokasi into a production-ready Progressive Web App (PWA).

Requirements:
## Installability
* Create a valid web app manifest.
* App name: Alokasi
* Short name: Alokasi
* Display mode: standalone
* Start URL: /
* Theme color and background color must match the existing design system.
* Add required icons (192x192 and 512x512).
* Add maskable icons for Android support.

## Service Worker
Implement a service worker with Workbox.
Caching strategy:
* App shell (CSS, JS, fonts, icons):
  Cache First
* Navigation requests:
  Network First with offline fallback
* Images:
  Stale While Revalidate
* API requests:
  Network First
* Cache versioning must be supported to prevent stale deployments.

## Offline Experience
Create an offline page.
When the user loses connection:
* Show a friendly offline screen.
* Preserve dashboard layout styling.
* Explain that internet is required to sync data.

Do not show browser error pages.

## Install Experience
* Detect install availability.
* Show custom "Install App" button.
* Hide button after installation.
* Handle beforeinstallprompt event properly.

## Mobile Experience
* Configure manifest for standalone display mode.
* Meet Add to Home Screen installability requirements on supported browsers.

## Laravel Integration
Current stack:
* Laravel 11
* Livewire 3
* Alpine.js
* Tailwind CSS

Requirements:
* Do not break existing routes.
* Do not break authentication.
* Service worker must not cache sensitive user-specific HTML.
* Authenticated data should always revalidate with the server.

## Security
* Never cache authenticated dashboard HTML permanently.
* Never cache user financial data offline unless explicitly designed.
* Logout must invalidate stale authenticated responses.

## Deliverables
1. manifest.webmanifest
2. service-worker.js
3. offline page
4. install prompt component
5. icon assets
6. registration logic
7. deployment notes
8. testing checklist