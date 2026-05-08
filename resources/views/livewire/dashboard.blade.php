<div class="min-w-0">
    <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 bg-white px-6 py-4 text-sm dark:border-slate-800 dark:bg-slate-950 lg:px-8">
        <div>
            <div class="text-xs font-medium uppercase text-gray-400 dark:text-slate-500">Dashboard</div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">Label Spending</h1>
        </div>

        <button
            type="button"
            x-on:click="theme = theme === 'dark' ? 'light' : 'dark'"
            class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white"
            aria-label="Toggle appearance"
            title="Toggle appearance"
        >
            <svg x-show="theme === 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
            </svg>
            <svg x-show="theme !== 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75 9.75 9.75 0 0 1 8.25 6c0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25 9.75 9.75 0 0 0 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
            </svg>
        </button>
    </header>

    <main class="space-y-6 px-6 py-6 lg:px-8">
        <section class="grid gap-3 md:grid-cols-3">
            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                <div class="text-xs font-medium uppercase text-gray-400 dark:text-slate-500">Total Expense</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-900 dark:text-slate-100">{{ $this->rupiah($totalExpense) }}</div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                <div class="text-xs font-medium uppercase text-gray-400 dark:text-slate-500">Active Labels</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-900 dark:text-slate-100">{{ $labelCount }}</div>
            </div>
            <div class="rounded-lg border border-gray-200 bg-white px-4 py-3 dark:border-slate-800 dark:bg-slate-900">
                <div class="text-xs font-medium uppercase text-gray-400 dark:text-slate-500">Top Label</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-900 dark:text-slate-100">{{ $topLabel['name'] ?? '-' }}</div>
            </div>
        </section>

        <section class="flex flex-wrap items-center justify-between gap-3">
            <h2 class="text-xl font-bold text-gray-900 dark:text-slate-100">Breakdown by Label</h2>
            <input
                wire:model.live.debounce.300ms="search"
                type="search"
                placeholder="Search label or expense"
                class="w-full rounded-lg bg-white px-3 py-2 text-sm shadow-sm ring-1 ring-gray-200 dark:bg-slate-900 dark:text-slate-100 dark:ring-slate-800 sm:w-72"
            >
        </section>

        <section class="space-y-4">
            @forelse ($labelBreakdown as $label)
                <article class="rounded-lg border border-gray-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 dark:text-slate-100">{{ $label['name'] }}</h3>
                            <p class="text-xs text-gray-500 dark:text-slate-400">{{ $label['transactions'] }} transactions</p>
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
                                    <div class="shrink-0 font-semibold text-gray-900 dark:text-slate-100">{{ $this->rupiah($item['total']) }}</div>
                                </div>
                                <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                                    <div class="h-full rounded-full bg-green-500" style="width: {{ $item['percentage'] }}%"></div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </article>
            @empty
                <div class="rounded-lg border border-dashed border-gray-300 px-6 py-12 text-center dark:border-slate-700">
                    <div class="text-lg font-semibold text-gray-900 dark:text-slate-100">No label spending found</div>
                    <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Create expenses with labels to fill this dashboard.</p>
                </div>
            @endforelse
        </section>
    </main>
</div>
