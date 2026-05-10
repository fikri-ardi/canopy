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
    <title>Canopy - Your Income Pal</title>
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

        <main class="min-w-0 flex-1 pb-24 md:pb-0">
            <div class="w-full">
                {{ $slot }}
            </div>
        </main>
    </div>
    <script>
        window.canopyDropdown = function () {
            return {
                open: false,
                style: '',
                toggle(trigger, menu) {
                    this.open ? this.close() : this.show(trigger, menu);
                },
                show(trigger, menu) {
                    this.open = true;
                    requestAnimationFrame(() => {
                        this.position(trigger, menu);
                        requestAnimationFrame(() => {
                            if (this.open) {
                                this.position(trigger, menu);
                            }
                        });
                    });
                },
                close() {
                    this.open = false;
                },
                position(trigger, menu) {
                    if (!trigger || !menu) {
                        return;
                    }

                    const rect = trigger.getBoundingClientRect();
                    const gap = 6;
                    const margin = 8;
                    const measuredHeight = menu.offsetHeight || menu.scrollHeight || menu.getBoundingClientRect().height || 240;
                    const menuHeight = Math.min(measuredHeight, window.innerHeight - (margin * 2));
                    const menuWidth = Math.min(rect.width, window.innerWidth - (margin * 2));
                    const left = Math.min(Math.max(margin, rect.left), window.innerWidth - menuWidth - margin);
                    const opensUp = window.innerHeight - rect.bottom < menuHeight && rect.top > window.innerHeight - rect.bottom;
                    const top = opensUp
                        ? Math.max(margin, rect.top - menuHeight - gap)
                        : Math.min(window.innerHeight - menuHeight - margin, rect.bottom + gap);

                    this.style = `top:${top}px;left:${left}px;width:${menuWidth}px;max-height:${menuHeight}px;`;
                },
            };
        };
    </script>
    @livewireScripts
</body>

</html>
