<div class="min-w-0" x-data="{budgetMenu: alokasiDropdown(), labelMenu: alokasiDropdown(), platformMenu: alokasiDropdown(), sortMenu: alokasiDropdown(), statusMenu: alokasiDropdown()}">
    <header class="app-header">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-emerald">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="M8.25 13.5h7.5M8.25 16.5h5.25M8.25 10.5h2.25" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Spends</div>
                    <h1 class="page-title">Transaction Explorer</h1>
                </div>
            </div>

            <div class="page-header-actions">
                <div class="header-insight-chip">
                    <span class="header-insight-dot bg-green-500"></span>
                    <span class="truncate">{{ number_format($transactionCount, 0, ',', '.') }} transactions</span>
                </div>

                <a href="{{ route('budgets') }}#expenses" wire:navigate class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Add Expense</span>
                </a>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="summary-grid">
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Filtered Total</div>
                        <div class="metric-value money-value">{{ $this->rupiah($totalAmount) }}</div>
                    </div>
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Transactions</div>
                        <div class="metric-value">{{ number_format($transactionCount, 0, ',', '.') }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 0 0-3.375-3.375h-1.5A1.125 1.125 0 0 1 13.5 7.125v-1.5A3.375 3.375 0 0 0 10.125 2.25H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 0 0-9-9Z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Average Spend</div>
                        <div class="metric-value money-value">{{ $this->rupiah($averageAmount) }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125C16.5 3.504 17.004 3 17.625 3h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Largest Spend</div>
                        <div class="metric-value money-value">{{ $this->rupiah($largestAmount) }}</div>
                    </div>
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-amber-50 text-amber-600 ring-1 ring-amber-100 dark:bg-amber-500/10 dark:text-amber-300 dark:ring-amber-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m2.25 18 9-13.5 9 13.5H2.25Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m0 3h.008v.008H12v-.008Z" />
                        </svg>
                    </span>
                </div>
            </div>
        </section>

        <section class="panel px-4 py-4">
            <div class="grid gap-3 md:grid-cols-2 xl:grid-cols-6">
                <div class="relative xl:col-span-2">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="pointer-events-none absolute left-3 top-1/2 size-4 -translate-y-1/2 text-gray-400">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m21 21-5.197-5.197M15.803 15.803A7.5 7.5 0 1 0 5.197 5.197a7.5 7.5 0 0 0 10.606 10.606Z" />
                    </svg>
                    <input wire:model.live.debounce.300ms="search" type="search" placeholder="Search expense, budget, label, platform" class="input-field pl-9">
                </div>

                <div>
                    <button x-ref="budgetTrigger" type="button" x-on:click.stop="budgetMenu.toggle($refs.budgetTrigger, $refs.budgetMenu)" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $budgetId === 'all' ? 'All budgets' : $budgets->firstWhere('id', (int) $budgetId)?->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <template x-teleport="body">
                        <div x-ref="budgetMenu" x-show="budgetMenu.open" x-cloak x-transition x-bind:style="budgetMenu.style" x-on:click.outside="budgetMenu.close()" x-on:resize.window="budgetMenu.close()" wire:key="spends-budget-menu" wire:ignore.self class="floating-select-menu">
                            <button type="button" x-on:click="budgetMenu.close()" wire:click="$set('budgetId', 'all')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">All budgets</button>
                            @foreach ($budgets as $budget)
                                <button type="button" x-on:click="budgetMenu.close()" wire:click="$set('budgetId', '{{ $budget->id }}')" wire:key="spends-budget-option-{{ $budget->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $budget->name }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>

                <div>
                    <button x-ref="labelTrigger" type="button" x-on:click.stop="labelMenu.toggle($refs.labelTrigger, $refs.labelMenu)" @disabled(! $labelsReady) class="btn-secondary w-full justify-between disabled:cursor-not-allowed disabled:opacity-50">
                        <span class="truncate">{{ $labelId === 'all' ? 'All labels' : ($labelId === 'unlabeled' ? 'Unlabeled' : $labels->firstWhere('id', (int) $labelId)?->name) }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    @if ($labelsReady)
                        <template x-teleport="body">
                            <div x-ref="labelMenu" x-show="labelMenu.open" x-cloak x-transition x-bind:style="labelMenu.style" x-on:click.outside="labelMenu.close()" x-on:resize.window="labelMenu.close()" wire:key="spends-label-menu" wire:ignore.self class="floating-select-menu">
                                <button type="button" x-on:click="labelMenu.close()" wire:click="$set('labelId', 'all')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">All labels</button>
                                <button type="button" x-on:click="labelMenu.close()" wire:click="$set('labelId', 'unlabeled')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">Unlabeled</button>
                                @foreach ($labels as $label)
                                    <button type="button" x-on:click="labelMenu.close()" wire:click="$set('labelId', '{{ $label->id }}')" wire:key="spends-label-option-{{ $label->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $label->name }}</button>
                                @endforeach
                            </div>
                        </template>
                    @endif
                </div>

                <div>
                    <button x-ref="platformTrigger" type="button" x-on:click.stop="platformMenu.toggle($refs.platformTrigger, $refs.platformMenu)" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $platformId === 'all' ? 'All platforms' : $platforms->firstWhere('id', (int) $platformId)?->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <template x-teleport="body">
                        <div x-ref="platformMenu" x-show="platformMenu.open" x-cloak x-transition x-bind:style="platformMenu.style" x-on:click.outside="platformMenu.close()" x-on:resize.window="platformMenu.close()" wire:key="spends-platform-menu" wire:ignore.self class="floating-select-menu">
                            <button type="button" x-on:click="platformMenu.close()" wire:click="$set('platformId', 'all')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">All platforms</button>
                            @foreach ($platforms as $platform)
                                <button type="button" x-on:click="platformMenu.close()" wire:click="$set('platformId', '{{ $platform->id }}')" wire:key="spends-platform-option-{{ $platform->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $platform->name }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>

                <div>
                    @php($sortLabels = ['latest' => 'Latest first', 'amount_desc' => 'Highest amount', 'amount_asc' => 'Lowest amount', 'name' => 'Name'])
                    <button x-ref="sortTrigger" type="button" x-on:click.stop="sortMenu.toggle($refs.sortTrigger, $refs.sortMenu)" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $sortLabels[$sort] ?? 'Latest first' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                    </button>
                    <template x-teleport="body">
                        <div x-ref="sortMenu" x-show="sortMenu.open" x-cloak x-transition x-bind:style="sortMenu.style" x-on:click.outside="sortMenu.close()" x-on:resize.window="sortMenu.close()" wire:key="spends-sort-menu" wire:ignore.self class="floating-select-menu">
                            @foreach ($sortLabels as $value => $label)
                                <button type="button" x-on:click="sortMenu.close()" wire:click="$set('sort', '{{ $value }}')" wire:key="spends-sort-option-{{ $value }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $label }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>

            <div class="mt-3 w-full md:w-64">
                <button x-ref="statusTrigger" type="button" x-on:click.stop="statusMenu.toggle($refs.statusTrigger, $refs.statusMenu)" class="btn-secondary w-full justify-between">
                    <span class="truncate">{{ $statusId === 'all' ? 'All statuses' : $statuses->firstWhere('id', (int) $statusId)?->body }}</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0"><path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" /></svg>
                </button>
                <template x-teleport="body">
                    <div x-ref="statusMenu" x-show="statusMenu.open" x-cloak x-transition x-bind:style="statusMenu.style" x-on:click.outside="statusMenu.close()" x-on:resize.window="statusMenu.close()" wire:key="spends-status-menu" wire:ignore.self class="floating-select-menu">
                        <button type="button" x-on:click="statusMenu.close()" wire:click="$set('statusId', 'all')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">All statuses</button>
                        @foreach ($statuses as $status)
                            <button type="button" x-on:click="statusMenu.close()" wire:click="$set('statusId', '{{ $status->id }}')" wire:key="spends-status-option-{{ $status->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $status->body }}</button>
                        @endforeach
                    </div>
                </template>
            </div>
        </section>

        <section class="table-shell">
            <table class="w-full min-w-[980px] table-fixed">
                <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500 dark:bg-slate-950 dark:text-slate-400">
                    <tr>
                        <th class="w-32 p-3 text-left">Date</th>
                        <th class="p-3 text-left">Expense</th>
                        <th class="p-3 text-left">Budget</th>
                        <th class="p-3 text-left">Label</th>
                        <th class="p-3 text-left">Platform</th>
                        <th class="p-3 text-left">Status</th>
                        <th class="w-36 p-3 text-right">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100 text-sm dark:divide-slate-800">
                    @forelse ($spends as $spend)
                        <tr wire:key="spends-row-{{ $spend->id }}" class="bg-white text-gray-800 transition hover:bg-gray-50 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800/60">
                            <td class="p-3 text-gray-500 dark:text-slate-400">{{ $spend->created_at?->format('d M Y') }}</td>
                            <td class="p-3">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $spend->name }}</div>
                            </td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->budget?->name }}</td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->label?->name ?? 'Unlabeled' }}</td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->platform?->name }}</td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->status?->body }}</td>
                            <td class="money-value p-3 text-right font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($spend->getRawOriginal('amount')) }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="px-3 py-12 text-center">
                                <span class="icon-box mx-auto">
                                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                                    </svg>
                                </span>
                                <div class="mt-3 font-semibold text-gray-950 dark:text-slate-50">No matching expenses</div>
                                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Adjust filters or add a new expense from the budget page.</p>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </section>

        <div>
            {{ $spends->links('components.spends-pagination') }}
        </div>
    </main>
</div>
