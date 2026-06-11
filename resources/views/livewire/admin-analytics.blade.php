<div class="min-w-0" wire:poll.30s>
    <header class="app-header">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M12 3.75 4.5 6.75v5.695A9 9 0 0 0 12 21a9 9 0 0 0 7.5-8.555V6.75L12 3.75Z" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Admin</div>
                    <h1 class="page-title">Analitik Pengguna</h1>
                </div>
            </div>
            <div class="hidden items-center gap-2 rounded-lg border border-gray-200 bg-white/60 px-3 py-2 text-xs font-medium text-gray-500 backdrop-blur dark:border-slate-800 dark:bg-slate-950/40 dark:text-slate-400 sm:flex">
                <span class="inline-flex size-2 rounded-full bg-green-500"></span>
                <span>Refresh otomatis 30 detik</span>
                <span class="text-gray-400 dark:text-slate-600">{{ $refreshedAt->format('H:i:s') }}</span>
            </div>
        </div>
    </header>

    <main class="space-y-5 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            @foreach ([
                ['label' => 'Aktif sekarang', 'value' => $activeNow, 'note' => '5 menit terakhir', 'tone' => 'green'],
                ['label' => 'DAU', 'value' => $dailyActiveUsers, 'note' => 'Terlihat hari ini', 'tone' => 'cyan'],
                ['label' => 'MAU', 'value' => $monthlyActiveUsers, 'note' => 'Terlihat bulan ini', 'tone' => 'violet'],
                ['label' => 'Pengguna', 'value' => $totalUsers, 'note' => 'Total akun', 'tone' => 'slate'],
            ] as $card)
                <div class="metric-card">
                    <div class="min-w-0">
                        <div class="metric-label">{{ $card['label'] }}</div>
                        <div class="metric-value-lg">{{ number_format($card['value'], 0, ',', '.') }}</div>
                        <div class="mt-1 text-xs text-gray-500 dark:text-slate-400">{{ $card['note'] }}</div>
                    </div>
                    <span @class([
                        'inline-flex size-10 shrink-0 items-center justify-center rounded-lg ring-1',
                        'bg-green-50 text-green-600 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20' => $card['tone'] === 'green',
                        'bg-cyan-50 text-cyan-600 ring-cyan-100 dark:bg-cyan-500/10 dark:text-cyan-300 dark:ring-cyan-500/20' => $card['tone'] === 'cyan',
                        'bg-violet-50 text-violet-600 ring-violet-100 dark:bg-violet-500/10 dark:text-violet-300 dark:ring-violet-500/20' => $card['tone'] === 'violet',
                        'bg-slate-50 text-slate-500 ring-slate-100 dark:bg-slate-800 dark:text-slate-300 dark:ring-slate-700' => $card['tone'] === 'slate',
                    ])>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3.75 13.5h4.5l2.25-6.75 4.5 13.5 2.25-6.75h3" />
                        </svg>
                    </span>
                </div>
            @endforeach
        </section>

        <section class="grid gap-5 xl:grid-cols-[minmax(0,0.95fr)_minmax(0,1.05fr)]">
            <div class="panel p-4 sm:p-5">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Sedang Aktif</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Pengguna yang terlihat dalam 5 menit terakhir.</p>
                    </div>
                    <span class="rounded-md bg-green-50 px-2.5 py-1 text-xs font-semibold text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20">{{ $currentlyActiveUsers->count() }}</span>
                </div>

                <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($currentlyActiveUsers as $user)
                        <div class="flex items-center gap-3 py-3">
                            <span class="inline-flex size-9 shrink-0 items-center justify-center rounded-lg bg-green-50 text-sm font-semibold text-green-600 ring-1 ring-green-100 dark:bg-green-500/10 dark:text-green-300 dark:ring-green-500/20">
                                {{ str($user->name)->substr(0, 1)->upper() }}
                            </span>
                            <div class="min-w-0 flex-1">
                                <div class="truncate text-sm font-semibold text-gray-950 dark:text-slate-50">{{ $user->name }}</div>
                                <div class="truncate text-xs text-gray-500 dark:text-slate-400">{{ $user->email }}</div>
                            </div>
                            <div class="shrink-0 text-right text-xs text-gray-400 dark:text-slate-500">
                                {{ $user->last_seen_at?->diffForHumans() }}
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                            Belum ada pengguna aktif sekarang.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel p-4 sm:p-5">
                <div>
                    <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Masukan Terbaru</h2>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Catatan terbaru yang dikirim dari Pengaturan.</p>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($recentFeedback as $feedback)
                        <div class="rounded-lg border border-gray-200 bg-white/70 p-3 dark:border-slate-800 dark:bg-slate-900/60">
                            <div class="flex items-center justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-semibold text-gray-950 dark:text-slate-50">{{ $feedback->user?->name ?? 'Pengguna terhapus' }}</div>
                                    <div class="truncate text-xs text-gray-500 dark:text-slate-400">{{ $feedback->created_at?->diffForHumans() }}</div>
                                </div>
                                <span class="shrink-0 rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold capitalize text-gray-500 dark:bg-slate-800 dark:text-slate-300">{{ $feedback->mood }}</span>
                            </div>
                            <p class="mt-3 text-sm leading-6 text-gray-600 dark:text-slate-300">{{ $feedback->message }}</p>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                            Belum ada masukan.
                        </div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="panel p-4 sm:p-5">
            @php
                $recentUserSortLabel = function (string $field, string $label) use ($recentUsersSortField, $recentUsersSortDirection) {
                    $isActive = $recentUsersSortField === $field;
                    $icon = $isActive && $recentUsersSortDirection === 'asc'
                        ? 'M4.5 15.75 12 8.25l7.5 7.5'
                        : 'm19.5 8.25-7.5 7.5-7.5-7.5';

                    return compact('isActive', 'icon', 'label');
                };
            @endphp

            <div>
                <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Pengguna Terbaru</h2>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Akun terbaru dan role yang dipakai.</p>
            </div>

            <div id="recent-users-table" class="mt-4 overflow-hidden rounded-lg border border-gray-200 dark:border-slate-800">
                <div class="hidden grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)_7rem_8.5rem_9rem] bg-gray-50 px-3 py-2 text-xs font-semibold uppercase text-gray-400 dark:bg-slate-900 dark:text-slate-500 md:grid">
                    @foreach ([
                        ['field' => 'name', 'label' => 'Pengguna', 'align' => 'text-left'],
                        ['field' => 'email', 'label' => 'Email', 'align' => 'text-left'],
                        ['field' => 'role', 'label' => 'Role', 'align' => 'text-left'],
                        ['field' => 'last_seen_at', 'label' => 'Terakhir terlihat', 'align' => 'text-right justify-end'],
                        ['field' => 'created_at', 'label' => 'Bergabung', 'align' => 'text-right justify-end'],
                    ] as $heading)
                        @php($sort = $recentUserSortLabel($heading['field'], $heading['label']))
                        <button type="button" wire:click="sortRecentUsers('{{ $heading['field'] }}')" class="{{ $heading['align'] }} inline-flex items-center gap-1 rounded-md px-1.5 py-1 transition hover:bg-white hover:text-gray-700 dark:hover:bg-slate-800 dark:hover:text-slate-200 {{ $sort['isActive'] ? 'text-green-600 dark:text-green-300' : '' }}">
                            <span>{{ $sort['label'] }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-3.5 {{ $sort['isActive'] ? 'opacity-100' : 'opacity-35' }}">
                                <path stroke-linecap="round" stroke-linejoin="round" d="{{ $sort['icon'] }}" />
                            </svg>
                        </button>
                    @endforeach
                </div>

                <div class="divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($recentUsers as $user)
                        <div class="grid gap-2 px-3 py-3 text-sm md:grid-cols-[minmax(0,1.1fr)_minmax(0,1fr)_7rem_8.5rem_9rem] md:items-center">
                            <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $user->name }}</div>
                            <div class="truncate text-gray-500 dark:text-slate-400">{{ $user->email }}</div>
                            <div>
                                <span class="rounded-md bg-gray-100 px-2 py-1 text-xs font-semibold capitalize text-gray-500 dark:bg-slate-800 dark:text-slate-300">{{ $user->role?->label ?? 'Pengguna' }}</span>
                            </div>
                            <div class="text-gray-500 dark:text-slate-400 md:text-right">
                                @if ($user->last_seen_at)
                                    <span class="block">{{ $user->last_seen_at->diffForHumans() }}</span>
                                    <span class="block text-xs text-gray-400 dark:text-slate-500">{{ $user->last_seen_at->format('d M, H:i') }}</span>
                                @else
                                    <span class="text-gray-400 dark:text-slate-500">Belum pernah</span>
                                @endif
                            </div>
                            <div class="text-gray-500 dark:text-slate-400 md:text-right">{{ $user->created_at?->format('d M Y') }}</div>
                        </div>
                    @empty
                        <div class="px-4 py-8 text-center text-sm text-gray-500 dark:text-slate-400">Belum ada pengguna.</div>
                    @endforelse
                </div>
            </div>

            @if ($recentUsersTotal > 0)
                <div class="mt-4 flex flex-col gap-3 border-t border-gray-100 pt-4 dark:border-slate-800 sm:flex-row sm:items-center sm:justify-between">
                    <div class="text-sm text-gray-500 dark:text-slate-400">
                        Menampilkan <span class="font-semibold text-gray-800 dark:text-slate-200">{{ $recentUsersShowing }}</span> dari <span class="font-semibold text-gray-800 dark:text-slate-200">{{ $recentUsersTotal }}</span> pengguna
                    </div>

                    <div class="flex w-full flex-col gap-2 sm:w-auto sm:flex-row">
                        @if ($recentUsersShowing < $recentUsersTotal)
                            <button type="button" wire:click="showMoreRecentUsers" aria-controls="recent-users-table" class="btn-secondary w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                                </svg>
                                Tampilkan {{ $recentUsersNextCount }} lagi
                            </button>
                        @endif

                        @if ($recentUsersShowing > 6)
                            <button type="button" wire:click="showLessRecentUsers" aria-controls="recent-users-table" class="btn-secondary w-full sm:w-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="m4.5 15.75 7.5-7.5 7.5 7.5" />
                                </svg>
                                Tampilkan lebih sedikit
                            </button>
                        @endif
                    </div>
                </div>
            @endif
        </section>
    </main>
</div>
