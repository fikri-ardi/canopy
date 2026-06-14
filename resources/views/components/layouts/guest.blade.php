<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @livewireStyles
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:opsz,wght@14..32,400..800&family=JetBrains+Mono:wght@400;500;600;700&display=swap" rel="stylesheet">
    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+icon">
    <link rel="manifest" href="/build/manifest.webmanifest">
    <meta name="theme-color" content="#22c55e">
    <meta name="apple-mobile-web-app-capable" content="yes">
    <meta name="apple-mobile-web-app-status-bar-style" content="default">
    <meta name="apple-mobile-web-app-title" content="Alokasi">
    <link rel="apple-touch-icon" href="/images/icons/icon-192x192.png">
    <title>{{ $title ?? 'Alokasi' }} - Teman Atur Uang</title>
</head>

<body class="h-full font-sans">
    <x-flash-banner />

    <main class="relative z-10 flex min-h-full items-center justify-center px-4 py-10">
        <div class="w-full max-w-md">
            <a href="/" wire:navigate class="mb-8 flex items-center justify-center gap-3">
                <span class="inline-flex size-11 items-center justify-center rounded-lg bg-green-50 text-green-500 ring-1 ring-green-100 dark:bg-green-500/10 dark:ring-green-500/20">
                    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="size-8">
                        <path fill-rule="evenodd" d="M12 2.25c-5.385 0-9.75 4.365-9.75 9.75s4.365 9.75 9.75 9.75 9.75-4.365 9.75-9.75S17.385 2.25 12 2.25ZM9 7.5A.75.75 0 0 0 9 9h1.5c.98 0 1.813.626 2.122 1.5H9A.75.75 0 0 0 9 12h3.622a2.251 2.251 0 0 1-2.122 1.5H9a.75.75 0 0 0-.53 1.28l3 3a.75.75 0 1 0 1.06-1.06L10.8 14.988A3.752 3.752 0 0 0 14.175 12H15a.75.75 0 0 0 0-1.5h-.825A3.733 3.733 0 0 0 13.5 9H15a.75.75 0 0 0 0-1.5H9Z" clip-rule="evenodd" />
                    </svg>
                </span>
                <span class="text-2xl font-bold text-gray-950 dark:text-slate-50">Alokasi</span>
            </a>

            {{ $slot }}

            <div class="mt-6 flex items-center justify-center gap-3 text-xs font-semibold text-gray-400 dark:text-slate-500">
                <a href="{{ route('privacy') }}" class="transition hover:text-green-600 dark:hover:text-green-400">Kebijakan Privasi</a>
                <span aria-hidden="true">/</span>
                <a href="{{ route('terms') }}" class="transition hover:text-green-600 dark:hover:text-green-400">Syarat</a>
            </div>
        </div>
    </main>
    @livewireScripts
</body>

</html>
