<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite('resources/css/app.css')
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <link rel="shortcut icon" href="/images/favicon.svg" type="image/svg+icon">
    <title>Implants - Your Income Pal</title>
</head>

<body class="h-full">
    <div class="min-h-full flex">
        <livewire:sidebar />

        <main class="min-w-0 flex-1">
            <div class="w-full">
                {{ $slot }}
            </div>
        </main>
    </div>
</body>

</html>
