import { defineConfig } from 'vite';
import laravel from 'laravel-vite-plugin';
import { VitePWA } from 'vite-plugin-pwa';

export default defineConfig({
    plugins: [
        laravel({
            input: [
                'resources/css/app.css',
                'resources/js/app.js'
            ],
            refresh: true,
        }),
        VitePWA({
            strategies: 'injectManifest',
            srcDir: 'resources/js',
            filename: 'service-worker.js',
            outDir: 'public',
            injectRegister: false,
            manifest: {
                name: 'Alokasi',
                short_name: 'Alokasi',
                description: 'Alokasi - Teman Atur Uang',
                theme_color: '#22c55e',
                background_color: '#f9fafb',
                display: 'standalone',
                start_url: '/',
                icons: [
                    {
                        src: '/images/icons/icon-192x192.png',
                        sizes: '192x192',
                        type: 'image/png'
                    },
                    {
                        src: '/images/icons/icon-512x512.png',
                        sizes: '512x512',
                        type: 'image/png'
                    },
                    {
                        src: '/images/icons/icon-192x192-maskable.png',
                        sizes: '192x192',
                        type: 'image/png',
                        purpose: 'maskable'
                    },
                    {
                        src: '/images/icons/icon-512x512-maskable.png',
                        sizes: '512x512',
                        type: 'image/png',
                        purpose: 'maskable'
                    }
                ]
            },
            injectManifest: {
                globDirectory: 'public',
                globPatterns: [
                    'build/assets/*.{js,css}',
                    'images/icons/*.png',
                    'images/favicon.svg'
                ]
            }
        })
    ],
});
