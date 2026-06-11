<div class="min-w-0" x-data="{ deleteAccount: false, budgetMode: 'all' }">
    <header class="app-header">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-slate">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M10.343 3.94c.09-.542.56-.94 1.11-.94h1.093c.55 0 1.02.398 1.11.94l.149.894c.07.424.348.78.748.936.236.092.466.19.69.3.38.185.833.143 1.184-.099l.737-.51a1.125 1.125 0 0 1 1.45.12l.773.774c.39.389.44 1.002.12 1.45l-.51.737c-.242.35-.284.804-.099 1.184.11.224.208.454.3.69.156.4.512.678.936.748l.894.149c.542.09.94.56.94 1.11v1.093c0 .55-.398 1.02-.94 1.11l-.894.149c-.424.07-.78.348-.936.748a7.02 7.02 0 0 1-.3.69c-.185.38-.143.833.099 1.184l.51.737c.32.448.27 1.061-.12 1.45l-.774.773a1.125 1.125 0 0 1-1.45.12l-.737-.51c-.35-.242-.804-.284-1.184-.099a7.02 7.02 0 0 1-.69.3c-.4.156-.678.512-.748.936l-.149.894c-.09.542-.56.94-1.11.94h-1.093c-.55 0-1.02-.398-1.11-.94l-.149-.894a1.125 1.125 0 0 0-.748-.936 7.02 7.02 0 0 1-.69-.3c-.38-.185-.833-.143-1.184.099l-.737.51a1.125 1.125 0 0 1-1.45-.12l-.773-.774a1.125 1.125 0 0 1-.12-1.45l.51-.737c.242-.35.284-.804.099-1.184a7.02 7.02 0 0 1-.3-.69 1.125 1.125 0 0 0-.936-.748l-.894-.149a1.125 1.125 0 0 1-.94-1.11v-1.093c0-.55.398-1.02.94-1.11l.894-.149c.424-.07.78-.348.936-.748.092-.236.19-.466.3-.69.185-.38.143-.833-.099-1.184l-.51-.737a1.125 1.125 0 0 1 .12-1.45l.774-.773a1.125 1.125 0 0 1 1.45-.12l.737.51c.35.242.804.284 1.184.099.224-.11.454-.208.69-.3.4-.156.678-.512.748-.936l.149-.894Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0Z" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Akun</div>
                    <h1 class="page-title">Pengaturan</h1>
                </div>
            </div>
        </div>
    </header>

    <main class="grid gap-5 px-4 py-5 sm:px-6 sm:py-6 lg:grid-cols-[15rem_minmax(0,1fr)] lg:px-8">
        <aside class="lg:sticky lg:top-5 lg:self-start">
            <div class="panel p-2">
                @foreach ([
                    ['href' => '#appearance', 'label' => 'Tampilan'],
                    ['href' => '#setup', 'label' => 'Setup Aplikasi'],
                    ['href' => '#profile', 'label' => 'Profil'],
                    ['href' => '#feedback', 'label' => 'Masukan'],
                    ['href' => '#security', 'label' => 'Keamanan'],
                    ['href' => '#data', 'label' => 'Ekspor / Impor'],
                    ['href' => '#legal', 'label' => 'Legal'],
                    ['href' => '#delete-account', 'label' => 'Hapus Akun'],
                    ['href' => '#logout', 'label' => 'Keluar'],
                ] as $item)
                    <a href="{{ $item['href'] }}" class="flex items-center justify-between rounded-md px-3 py-2 text-sm font-semibold text-gray-600 transition hover:bg-green-50 hover:text-green-600 dark:text-slate-300 dark:hover:bg-green-500/10 dark:hover:text-green-300">
                        <span>{{ $item['label'] }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" class="size-4 text-gray-300 dark:text-slate-600">
                            <path fill-rule="evenodd" d="M8.22 5.22a.75.75 0 0 1 1.06 0l4.25 4.25a.75.75 0 0 1 0 1.06l-4.25 4.25a.75.75 0 0 1-1.06-1.06L11.94 10 8.22 6.28a.75.75 0 0 1 0-1.06Z" clip-rule="evenodd" />
                        </svg>
                    </a>
                @endforeach
            </div>
        </aside>

        <div class="min-w-0 space-y-5">
            <section id="appearance" class="panel scroll-mt-5 p-4 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Tampilan</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Pilih mode visual untuk ruang kerja kamu.</p>
                    </div>

                    <div class="inline-flex w-full rounded-lg border border-slate-200 bg-white/70 p-1 dark:border-slate-700 dark:bg-slate-900/70 sm:w-auto">
                        <button
                            type="button"
                            x-on:click="theme = 'light'"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition sm:flex-none"
                            x-bind:class="theme !== 'dark' ? 'bg-white text-slate-950 shadow-sm dark:bg-slate-100 dark:text-slate-950' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                            aria-label="Gunakan tampilan terang"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0Z" />
                            </svg>
                            Terang
                        </button>

                        <button
                            type="button"
                            x-on:click="theme = 'dark'"
                            class="inline-flex flex-1 items-center justify-center gap-2 rounded-md px-3 py-2 text-sm font-medium transition sm:flex-none"
                            x-bind:class="theme === 'dark' ? 'bg-slate-950 text-white shadow-sm dark:bg-slate-100 dark:text-slate-950' : 'text-slate-500 hover:text-slate-800 dark:text-slate-400 dark:hover:text-slate-200'"
                            aria-label="Gunakan tampilan gelap"
                        >
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75 9.75 9.75 0 0 1 8.25 6c0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25 9.75 9.75 0 0 0 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                            </svg>
                            Gelap
                        </button>
                    </div>
                </div>
            </section>

            <section id="setup" class="panel scroll-mt-5 p-4 sm:p-5">
                <div>
                    <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Setup Aplikasi</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Kelola bagian kecil yang dipakai oleh pengeluaran dan rencana.</p>
                </div>

                <div class="mt-4 grid gap-3 sm:grid-cols-3">
                    <a href="{{ route('labels') }}" wire:navigate class="group flex min-w-0 items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm transition hover:border-green-200 hover:bg-green-50/60 dark:border-slate-800 dark:bg-slate-900/60 dark:hover:border-green-500/30 dark:hover:bg-green-500/10">
                        <span class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-emerald-50 text-emerald-500 ring-1 ring-emerald-100 dark:bg-emerald-500/10 dark:ring-emerald-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 6.75h.008v.008H6.75V6.75Z" />
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-semibold text-gray-900 dark:text-slate-100">Label</span>
                            <span class="block truncate text-xs text-gray-500 dark:text-slate-400">Kategori pengeluaran</span>
                        </span>
                    </a>

                    <a href="{{ route('platforms') }}" wire:navigate class="group flex min-w-0 items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm transition hover:border-green-200 hover:bg-green-50/60 dark:border-slate-800 dark:bg-slate-900/60 dark:hover:border-green-500/30 dark:hover:bg-green-500/10">
                        <span class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-cyan-50 text-cyan-500 ring-1 ring-cyan-100 dark:bg-cyan-500/10 dark:ring-cyan-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 8.25h19.5m-18 0V6A2.25 2.25 0 0 1 6 3.75h12A2.25 2.25 0 0 1 20.25 6v12A2.25 2.25 0 0 1 18 20.25H6A2.25 2.25 0 0 1 3.75 18V8.25Zm3 6h4.5" />
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-semibold text-gray-900 dark:text-slate-100">Platform</span>
                            <span class="block truncate text-xs text-gray-500 dark:text-slate-400">Bank dan dompet digital</span>
                        </span>
                    </a>

                    <a href="{{ route('statuses') }}" wire:navigate class="group flex min-w-0 items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm transition hover:border-green-200 hover:bg-green-50/60 dark:border-slate-800 dark:bg-slate-900/60 dark:hover:border-green-500/30 dark:hover:bg-green-500/10">
                        <span class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-violet-50 text-violet-500 ring-1 ring-violet-100 dark:bg-violet-500/10 dark:ring-violet-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M4.5 6.75h15M4.5 17.25h15M6.75 21h10.5A2.25 2.25 0 0 0 19.5 18.75V5.25A2.25 2.25 0 0 0 17.25 3H6.75A2.25 2.25 0 0 0 4.5 5.25v13.5A2.25 2.25 0 0 0 6.75 21Z" />
                            </svg>
                        </span>
                        <span class="min-w-0 flex-1">
                            <span class="block font-semibold text-gray-900 dark:text-slate-100">Status</span>
                            <span class="block truncate text-xs text-gray-500 dark:text-slate-400">Status alokasi</span>
                        </span>
                    </a>
                </div>
            </section>

            <section id="profile" class="panel scroll-mt-5 p-4 sm:p-5">
                <div class="flex items-start justify-between gap-4">
                    <div>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Profil</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Jaga identitas akun kamu tetap terbaru.</p>
                    </div>
                </div>

                <form wire:submit="updateProfile" class="mt-4 grid gap-4 sm:grid-cols-2">
                    <div>
                        <label for="settings-name" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Nama</label>
                        <input id="settings-name" wire:model="name" type="text" class="input-field mt-1">
                        @error('name')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="settings-email" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Email</label>
                        <input id="settings-email" wire:model="email" type="email" class="input-field mt-1">
                        @error('email')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="sm:col-span-2">
                        <button type="submit" wire:loading.attr="disabled" wire:target="updateProfile" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                            </svg>
                            <span wire:loading.remove wire:target="updateProfile">Simpan Profil</span>
                            <span wire:loading wire:target="updateProfile">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </section>

            <section id="feedback" class="panel scroll-mt-5 p-4 sm:p-5">
                <div class="grid gap-5 lg:grid-cols-[minmax(0,0.95fr)_minmax(0,1.35fr)] lg:items-start">
                    <div>
                        <span class="inline-flex size-10 items-center justify-center rounded-lg bg-green-50 text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M8.625 9.75h6.75m-6.75 3h3.75M4.5 5.25A2.25 2.25 0 0 1 6.75 3h10.5a2.25 2.25 0 0 1 2.25 2.25v8.25a2.25 2.25 0 0 1-2.25 2.25H12l-4.5 3v-3H6.75a2.25 2.25 0 0 1-2.25-2.25V5.25Z" />
                            </svg>
                        </span>
                        <h2 class="mt-3 text-base font-bold text-gray-950 dark:text-slate-50">Masukan</h2>
                        <p class="mt-1 text-sm leading-6 text-gray-500 dark:text-slate-400">Ceritakan apa yang berguna, membingungkan, atau perlu diperbaiki. Catatan singkat juga cukup.</p>
                    </div>

                    <form wire:submit="sendFeedback" class="space-y-4">
                        <div class="grid gap-2 sm:grid-cols-3">
                            @foreach ([
                                ['value' => 'idea', 'label' => 'Ide', 'icon' => 'M12 18v-5.25m0 0A3.75 3.75 0 1 0 8.25 9m3.75 3.75A3.75 3.75 0 0 1 15.75 9M9.75 18h4.5M10.5 21h3'],
                                ['value' => 'issue', 'label' => 'Masalah', 'icon' => 'M12 9v3.75m0 3.75h.008v.008H12v-.008ZM10.29 3.86 1.82 18a2.25 2.25 0 0 0 1.93 3.375h16.5A2.25 2.25 0 0 0 22.18 18L13.71 3.86a2.25 2.25 0 0 0-3.42 0Z'],
                                ['value' => 'love', 'label' => 'Suka', 'icon' => 'M21 8.25c0 6.375-9 10.5-9 10.5s-9-4.125-9-10.5A4.875 4.875 0 0 1 12 5.25a4.875 4.875 0 0 1 9 3Z'],
                            ] as $option)
                                <label class="cursor-pointer">
                                    <input type="radio" wire:model="feedbackMood" value="{{ $option['value'] }}" class="peer sr-only">
                                    <span class="flex items-center gap-2 rounded-lg border border-gray-200 bg-white/70 px-3 py-2 text-sm font-medium text-gray-600 transition peer-checked:border-green-200 peer-checked:bg-green-50 peer-checked:text-green-700 dark:border-slate-700 dark:bg-slate-900/70 dark:text-slate-300 dark:peer-checked:border-green-500/30 dark:peer-checked:bg-green-500/10 dark:peer-checked:text-green-300">
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.6" stroke="currentColor" class="size-4">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $option['icon'] }}" />
                                        </svg>
                                        {{ $option['label'] }}
                                    </span>
                                </label>
                            @endforeach
                        </div>

                        <div>
                            <label for="settings-feedback-message" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Pesan</label>
                            <textarea id="settings-feedback-message" wire:model="feedbackMessage" rows="4" maxlength="1200" placeholder="Apa yang perlu dibuat lebih enak di Alokasi?" class="input-field mt-1 resize-none"></textarea>
                            @error('feedbackMessage')
                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                            @enderror
                        </div>

                        <button type="submit" wire:loading.attr="disabled" wire:target="sendFeedback" class="btn-primary w-full sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 12 3.269 3.125A59.77 59.77 0 0 1 21.485 12 59.768 59.768 0 0 1 3.27 20.875L6 12Zm0 0h7.5" />
                            </svg>
                            <span wire:loading.remove wire:target="sendFeedback">Kirim Masukan</span>
                            <span wire:loading wire:target="sendFeedback">Mengirim...</span>
                        </button>
                    </form>
                </div>
            </section>

            <section id="security" class="panel scroll-mt-5 p-4 sm:p-5">
                <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Keamanan</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">
                    {{ $hasPassword ? 'Ubah password yang dipakai untuk masuk dengan email.' : 'Buat password supaya akun sosial ini juga bisa masuk dengan email.' }}
                </p>

                <form wire:submit="updatePassword" class="mt-4 grid gap-4 sm:grid-cols-3">
                    @if ($hasPassword)
                        <div>
                            <label for="settings-current-password" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Password Saat Ini</label>
                            <input id="settings-current-password" wire:model="currentPassword" type="password" autocomplete="current-password" class="input-field mt-1">
                            @error('currentPassword')
                                <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

                    <div>
                        <label for="settings-password" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">{{ $hasPassword ? 'Password Baru' : 'Password' }}</label>
                        <input id="settings-password" wire:model="password" type="password" autocomplete="new-password" class="input-field mt-1">
                        @error('password')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div>
                        <label for="settings-password-confirmation" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Konfirmasi Password</label>
                        <input id="settings-password-confirmation" wire:model="password_confirmation" type="password" class="input-field mt-1">
                    </div>

                    <div class="sm:col-span-3">
                        <button type="submit" wire:loading.attr="disabled" wire:target="updatePassword" class="btn-primary">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 10.5V6.75a4.5 4.5 0 1 0-9 0v3.75m-.75 0h10.5A2.25 2.25 0 0 1 19.5 12.75v6A2.25 2.25 0 0 1 17.25 21H6.75A2.25 2.25 0 0 1 4.5 18.75v-6A2.25 2.25 0 0 1 6.75 10.5Z" />
                            </svg>
                            <span wire:loading.remove wire:target="updatePassword">{{ $hasPassword ? 'Perbarui Password' : 'Atur Password' }}</span>
                            <span wire:loading wire:target="updatePassword">Menyimpan...</span>
                        </button>
                    </div>
                </form>
            </section>

            <section id="data" class="grid scroll-mt-5 gap-5 xl:grid-cols-[minmax(0,1.6fr)_minmax(18rem,0.9fr)]">
                <div class="panel p-4 sm:p-5">
                    <div class="flex flex-col gap-3 sm:flex-row sm:items-start sm:justify-between">
                        <div>
                            <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Ekspor Data</h2>
                            <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Unduh rencana, pengeluaran, dan data akun terkait. Format Excel dan spreadsheet memuat semua sheet backup.</p>
                        </div>
                        <span class="inline-flex w-fit items-center rounded-md bg-green-50 px-2.5 py-1 text-xs font-bold uppercase text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20">Siap</span>
                    </div>

                    <form method="GET" action="{{ route('settings.export.budgets') }}" class="mt-5 space-y-5">
                        <div class="grid gap-3 sm:grid-cols-3">
                            @foreach ([
                                ['value' => 'csv', 'label' => 'CSV', 'note' => 'Baris pengeluaran'],
                                ['value' => 'xlsx', 'label' => 'Excel', 'note' => 'Backup penuh'],
                                ['value' => 'ods', 'label' => 'Spreadsheet', 'note' => 'Backup penuh'],
                            ] as $format)
                                <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm shadow-sm transition hover:border-green-200 hover:bg-green-50/50 dark:border-slate-700 dark:bg-slate-900/70 dark:hover:border-green-500/30 dark:hover:bg-green-500/10">
                                    <input type="radio" name="format" value="{{ $format['value'] }}" @checked($format['value'] === 'xlsx') class="size-4 border-gray-300 text-green-500 focus:ring-green-400">
                                    <span class="min-w-0">
                                        <span class="block font-bold text-gray-950 dark:text-slate-50">{{ $format['label'] }}</span>
                                        <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $format['note'] }}</span>
                                    </span>
                                </label>
                            @endforeach
                        </div>
                        @error('format')
                            <div class="text-xs text-red-500">{{ $message }}</div>
                        @enderror

                        <div class="grid gap-3 sm:grid-cols-2">
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                                <input type="radio" name="budget_mode" value="all" x-model="budgetMode" class="size-4 border-gray-300 text-green-500 focus:ring-green-400">
                                <span>
                                    <span class="block font-bold text-gray-950 dark:text-slate-50">Semua Rencana</span>
                                    <span class="block text-xs text-gray-500 dark:text-slate-400">{{ $budgets->count() }} rencana tersedia</span>
                                </span>
                            </label>
                            <label class="flex cursor-pointer items-center gap-3 rounded-lg border border-gray-200 bg-white/70 p-3 text-sm dark:border-slate-700 dark:bg-slate-900/70">
                                <input type="radio" name="budget_mode" value="selected" x-model="budgetMode" class="size-4 border-gray-300 text-green-500 focus:ring-green-400">
                                <span>
                                    <span class="block font-bold text-gray-950 dark:text-slate-50">Rencana Terpilih</span>
                                    <span class="block text-xs text-gray-500 dark:text-slate-400">Pilih satu atau beberapa rencana</span>
                                </span>
                            </label>
                        </div>

                        <div class="rounded-lg border border-gray-200 bg-gray-50/70 p-3 dark:border-slate-800 dark:bg-slate-950/40">
                            <div class="grid gap-2 sm:grid-cols-2 xl:grid-cols-3">
                                @forelse ($budgets as $budget)
                                    <label class="flex min-w-0 items-center gap-2 rounded-md bg-white px-3 py-2 text-sm ring-1 ring-gray-200 transition dark:bg-slate-900 dark:ring-slate-700" x-bind:class="budgetMode === 'all' ? 'opacity-55' : ''">
                                        <input type="checkbox" name="budgets[]" value="{{ $budget->id }}" x-bind:disabled="budgetMode === 'all'" class="size-4 rounded border-gray-300 text-green-500 focus:ring-green-400">
                                        <span class="min-w-0 flex-1 truncate font-semibold text-gray-700 dark:text-slate-200">{{ $budget->name }}</span>
                                        <span class="shrink-0 text-xs text-gray-400 dark:text-slate-500">{{ $budget->spends_count }}</span>
                                    </label>
                                @empty
                                    <div class="rounded-md border border-dashed border-gray-200 px-3 py-6 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400 sm:col-span-2 xl:col-span-3">Belum ada rencana.</div>
                                @endforelse
                            </div>
                        </div>
                        @error('budgets')
                            <div class="text-xs text-red-500">{{ $message }}</div>
                        @enderror
                        @error('budgets.*')
                            <div class="text-xs text-red-500">{{ $message }}</div>
                        @enderror

                        <button type="submit" @disabled($budgets->isEmpty()) class="btn-primary w-full disabled:cursor-not-allowed disabled:opacity-50 sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M7.5 10.5 12 15m0 0 4.5-4.5M12 15V3" />
                            </svg>
                            Ekspor Data
                        </button>
                    </form>
                </div>

                <div class="panel p-4 sm:p-5">
                    <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Impor Data</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Unggah file ekspor Alokasi untuk memulihkan atau menggabungkan baris rencana dan pengeluaran.</p>

                    <form wire:submit="importData" class="mt-5 rounded-lg border border-dashed border-gray-300 bg-gray-50/70 p-4 dark:border-slate-700 dark:bg-slate-950/40">
                        <label for="settings-import-file" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">File</label>
                        <input id="settings-import-file" wire:model="importFile" type="file" accept=".csv,.txt,.xlsx,.ods" class="input-field mt-1">
                        @error('importFile')
                            <div class="mt-2 text-xs text-red-500">{{ $message }}</div>
                        @enderror

                        <button type="submit" wire:loading.attr="disabled" wire:target="importFile,importData" class="btn-secondary mt-3 w-full disabled:cursor-not-allowed disabled:opacity-60">
                            <span wire:loading.remove wire:target="importData">Impor Data</span>
                            <span wire:loading wire:target="importData">Mengimpor...</span>
                        </button>
                    </form>
                </div>
            </section>

            <section id="legal" class="panel scroll-mt-5 p-4 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Legal</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Lihat cara Alokasi menangani data kamu dan syarat penggunaan aplikasi.</p>
                    </div>
                    <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                        <a href="{{ route('privacy') }}" class="btn-secondary w-full sm:w-auto">Kebijakan Privasi</a>
                        <a href="{{ route('terms') }}" class="btn-secondary w-full sm:w-auto">Syarat</a>
                    </div>
                </div>
            </section>

            <section id="delete-account" class="panel scroll-mt-5 border-red-200/80 p-4 dark:border-red-500/20 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-red-600 dark:text-red-300">Hapus Akun</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Hapus akun kamu dan semua data Alokasi milik akun ini.</p>
                    </div>
                    <button type="button" x-on:click="deleteAccount = true" class="btn-danger w-full sm:w-auto">
                        Hapus Akun
                    </button>
                </div>
            </section>

            <section id="logout" class="panel scroll-mt-5 p-4 sm:p-5">
                <div class="flex flex-col gap-4 sm:flex-row sm:items-center sm:justify-between">
                    <div>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Keluar</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Akhiri sesi ini dan kembali ke layar masuk.</p>
                    </div>
                    <form method="POST" action="{{ route('logout') }}" class="w-full sm:w-auto">
                        @csrf
                        <button type="submit" class="btn-secondary w-full border-red-200 text-red-600 hover:bg-red-50 hover:text-red-700 dark:border-red-500/20 dark:text-red-300 dark:hover:bg-red-500/10 sm:w-auto">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 9V5.25A2.25 2.25 0 0 0 13.5 3h-6A2.25 2.25 0 0 0 5.25 5.25v13.5A2.25 2.25 0 0 0 7.5 21h6a2.25 2.25 0 0 0 2.25-2.25V15m3 0 3-3m0 0-3-3m3 3H9" />
                            </svg>
                            Keluar
                        </button>
                    </form>
                </div>
            </section>
        </div>
    </main>

    <div x-show="deleteAccount" x-cloak x-transition class="modal-backdrop">
        <div x-on:click.away="deleteAccount = false" class="modal-panel">
            <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Hapus Akun</div>
            <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">Ini akan menghapus akun, rencana, pengeluaran, label, platform, dan status kamu.</p>

            <div class="mt-4">
                <label for="delete-account-password" class="text-xs font-semibold uppercase text-gray-500 dark:text-slate-400">Password</label>
                <input id="delete-account-password" wire:model="deletePassword" type="password" class="input-field mt-1">
                @error('deletePassword')
                    <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                @enderror
            </div>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="deleteAccount = false" class="btn-secondary">Batal</button>
                <button type="button" wire:click="deleteAccount" class="btn-danger">Hapus</button>
            </div>
        </div>
    </div>
</div>
