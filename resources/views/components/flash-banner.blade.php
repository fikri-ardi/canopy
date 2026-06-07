@php
    $statusMessages = [
        'verification-link-sent' => [
            'tone' => 'success',
            'title' => 'Email verifikasi terkirim',
            'message' => 'Cek inbox kamu. Link baru sudah siap dipakai untuk mengaktifkan akun.',
        ],
        'email-verified' => [
            'tone' => 'success',
            'title' => 'Email berhasil diverifikasi',
            'message' => 'Akun kamu sudah aktif. Selamat datang kembali di Alokasi.',
        ],
    ];

    $flash = null;

    if (session('success')) {
        $flash = ['tone' => 'success', 'title' => 'Berhasil', 'message' => session('success')];
    } elseif (session('error')) {
        $flash = ['tone' => 'error', 'title' => 'Ada yang perlu dicek', 'message' => session('error')];
    } elseif (session('status') && isset($statusMessages[session('status')])) {
        $flash = $statusMessages[session('status')];
    }
@endphp

@if ($flash)
    <div class="flash-notification flash-notification-{{ $flash['tone'] }}" data-flash-banner role="status" aria-live="polite">
        <div class="flash-icon" aria-hidden="true">
            @if ($flash['tone'] === 'error')
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
                </svg>
            @else
                <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.236 4.45-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l3.753-5.16Z" clip-rule="evenodd" />
                </svg>
            @endif
        </div>

        <div class="min-w-0 flex-1">
            <div class="flash-title">{{ $flash['title'] }}</div>
            <div class="flash-message">{{ $flash['message'] }}</div>
        </div>

        <button type="button" class="flash-close" data-flash-dismiss aria-label="Tutup notifikasi">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
            </svg>
        </button>
    </div>

@endif

<div
    x-data="alokasiFlash()"
    x-on:alokasi-flash.window="show($event.detail)"
    x-show="visible"
    x-cloak
    x-transition
    class="flash-notification"
    x-bind:class="'flash-notification-' + flash.tone"
    role="status"
    aria-live="polite"
>
    <div class="flash-icon" aria-hidden="true">
        <template x-if="flash.tone === 'error'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16ZM8.28 7.22a.75.75 0 0 0-1.06 1.06L8.94 10l-1.72 1.72a.75.75 0 1 0 1.06 1.06L10 11.06l1.72 1.72a.75.75 0 1 0 1.06-1.06L11.06 10l1.72-1.72a.75.75 0 0 0-1.06-1.06L10 8.94 8.28 7.22Z" clip-rule="evenodd" />
            </svg>
        </template>

        <template x-if="flash.tone !== 'error'">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
                <path fill-rule="evenodd" d="M10 18a8 8 0 1 0 0-16 8 8 0 0 0 0 16Zm3.857-9.809a.75.75 0 0 0-1.214-.882l-3.236 4.45-1.88-1.88a.75.75 0 1 0-1.06 1.061l2.5 2.5a.75.75 0 0 0 1.137-.089l3.753-5.16Z" clip-rule="evenodd" />
            </svg>
        </template>
    </div>

    <div class="min-w-0 flex-1">
        <div class="flash-title" x-text="flash.title"></div>
        <div class="flash-message" x-text="flash.message"></div>
    </div>

    <button type="button" class="flash-close" x-on:click="close" aria-label="Tutup notifikasi">
        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4">
            <path d="M6.28 5.22a.75.75 0 0 0-1.06 1.06L8.94 10l-3.72 3.72a.75.75 0 1 0 1.06 1.06L10 11.06l3.72 3.72a.75.75 0 1 0 1.06-1.06L11.06 10l3.72-3.72a.75.75 0 0 0-1.06-1.06L10 8.94 6.28 5.22Z" />
        </svg>
    </button>
</div>

<script>
    window.alokasiFlash = window.alokasiFlash || function () {
        return {
            visible: false,
            timeout: null,
            flash: {
                tone: 'success',
                title: '',
                message: '',
            },
            show(detail) {
                window.clearTimeout(this.timeout);

                this.flash = {
                    tone: detail.tone || 'success',
                    title: detail.title || 'Berhasil',
                    message: detail.message || '',
                };
                this.visible = true;
                this.timeout = window.setTimeout(() => this.close(), 5200);
            },
            close() {
                this.visible = false;
            },
        };
    };

    (() => {
        const banners = document.querySelectorAll('[data-flash-banner]');

        banners.forEach((banner) => {
            const close = () => {
                if (banner.classList.contains('is-leaving')) {
                    return;
                }

                banner.classList.add('is-leaving');
                window.setTimeout(() => banner.remove(), 260);
            };

            banner.querySelector('[data-flash-dismiss]')?.addEventListener('click', close);
            window.setTimeout(close, 5200);
        });
    })();
</script>
