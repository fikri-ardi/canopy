<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    class="h-full dark"
>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @livewireStyles
    @vite('resources/css/app.css')
    <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+icon">
    <title>Implants - Your Income Pal</title>
</head>

<body
    class="h-full font-sans"
    x-data="{ theme: localStorage.getItem('theme') || 'dark' }"
    x-init="
        document.documentElement.classList.toggle('dark', theme === 'dark');
        $watch('theme', value => {
            localStorage.setItem('theme', value);
            document.documentElement.classList.toggle('dark', value === 'dark');
        });
    "
>
    <div class="flex min-h-full">
        <livewire:sidebar />

        <main class="min-w-0 flex-1 pb-20 md:pb-0">
            <div class="w-full">
                {{ $slot }}
            </div>
        </main>
    </div>
    @livewireScripts
</body>

</html>
