<div class="min-w-0">
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Dashboard</div>
                <h1 class="page-title">Label Spending</h1>
                <p class="page-subtitle">See where money goes across labels and repeated expense names.</p>
            </div>

            <button type="button" x-on:click="theme = theme === 'dark' ? 'light' : 'dark'" class="btn-icon" aria-label="Toggle appearance" title="Toggle appearance">
                <svg x-show="theme === 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                </svg>
                <svg x-show="theme !== 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75 9.75 9.75 0 0 1 8.25 6c0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25 9.75 9.75 0 0 0 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                </svg>
            </button>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="grid gap-3 md:grid-cols-3">
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Expense</div>
                        <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($totalExpense) }}</div>
                    </div>
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m19.5 0h-.75a.75.75 0 0 1-.75-.75V4.5m0 0H3.75m16.5 0c.621 0 1.125.504 1.125 1.125v9.75c0 .621-.504 1.125-1.125 1.125H3.75A1.125 1.125 0 0 1 2.625 15.375v-9.75C2.625 5.004 3.129 4.5 3.75 4.5" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div>
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Active Labels</div>
                        <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $labelCount }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Top Label</div>
                        <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $topLabel['name'] ?? '-' }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M16.5 18.75h-9m9 0a3 3 0 0 1 3 3h-15a3 3 0 0 1 3-3m9 0v-3.375c0-.621-.504-1.125-1.125-1.125h-6.75c-.621 0-1.125.504-1.125 1.125V18.75m9 0H7.5" />
                        </svg>
                    </span>
                </div>
            </div>
        </section>

        <section class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-950 dark:text-slate-50">Breakdown by Label</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400">Grouped totals, sorted by spending volume.</p>
            </div>
            <div class="relative w-full sm:w-80">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197M15.803 15.803A7.5 7.5 0 1 0 5.197 5.197a7.5 7.5 0 0 0 10.606 10.606Z" />
                </svg>
                <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search label or expense" class="input-field pl-9">
            </div>
        </section>

        <section class="space-y-4">
            @forelse ($labelBreakdown as $label)
                <article class="panel px-4 py-4">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="icon-box-muted">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                                </svg>
                            </span>
                            <div>
                                <h3 class="text-lg font-bold text-gray-950 dark:text-slate-50">{{ $label['name'] }}</h3>
                                <p class="text-xs text-gray-500 dark:text-slate-400">{{ $label['transactions'] }} transactions</p>
                            </div>
                        </div>
                        <div class="text-right text-lg font-bold text-green-500">{{ $this->rupiah($label['total']) }}</div>
                    </div>

                    <div class="mt-4 space-y-3">
                        @foreach ($label['items'] as $item)
                            <div>
                                <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                    <div class="min-w-0">
                                        <div class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $item['name'] }}</div>
                                        <div class="text-xs text-gray-400 dark:text-slate-500">{{ $item['transactions'] }}x</div>
                                    </div>
                                    <div class="shrink-0 font-semibold text-gray-950 dark:text-slate-50">{{ $this->rupiah($item['total']) }}</div>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-green-500" style="width: {{ $item['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="panel border-dashed px-6 py-12 text-center">
                    <span class="icon-box mx-auto">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                    </span>
                    <div class="mt-4 text-lg font-semibold text-gray-950 dark:text-slate-50">No label spending found</div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Create expenses with labels to fill this dashboard.</p>
                </div>
            @endforelse
        </section>
    </main>
</div>
