<div class="min-w-0">
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Dashboard</div>
                <h1 class="page-title">Financial Command Center</h1>
                <p class="page-subtitle">A quick read on income, spending pressure, budgets, and recent movement.</p>
            </div>

            <div class="flex items-center gap-2">
                <a href="{{ route('budgets') }}" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>Manage Budget</span>
                </a>
                <button type="button" x-on:click="theme = theme === 'dark' ? 'light' : 'dark'" class="btn-icon" aria-label="Toggle appearance" title="Toggle appearance">
                    <svg x-show="theme === 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386-1.591 1.591M21 12h-2.25m-.386 6.364-1.591-1.591M12 18.75V21m-4.773-4.227-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 1 1-7.5 0 3.75 3.75 0 0 1 7.5 0Z" />
                    </svg>
                    <svg x-show="theme !== 'dark'" x-cloak xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.72 9.72 0 0 1 18 15.75 9.75 9.75 0 0 1 8.25 6c0-1.33.266-2.597.748-3.752A9.753 9.753 0 0 0 3 11.25 9.75 9.75 0 0 0 12.75 21a9.753 9.753 0 0 0 9.002-5.998Z" />
                    </svg>
                </button>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="summary-grid">
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Income</div>
                        <div class="metric-value">{{ $this->rupiah($totalIncome) }}</div>
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
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Total Expense</div>
                        <div class="metric-value">{{ $this->rupiah($totalExpense) }}</div>
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
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Remaining</div>
                        <div class="{{ $remainingBalance < 0 ? 'metric-value-danger' : 'metric-value' }}">{{ $this->rupiah($remainingBalance) }}</div>
                    </div>
                    <span class="{{ $remainingBalance < 0 ? 'inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20' : 'icon-box-muted' }}">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Transactions</div>
                        <div class="metric-value">{{ $transactionCount }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Active Labels</div>
                        <div class="metric-value">{{ $labelCount }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9.568 3H5.25A2.25 2.25 0 0 0 3 5.25v4.318c0 .597.237 1.17.659 1.591l9.581 9.581c.699.699 1.78.872 2.607.33a18.095 18.095 0 0 0 5.223-5.223c.542-.827.369-1.908-.33-2.607L11.16 3.66A2.25 2.25 0 0 0 9.568 3Z" />
                        </svg>
                    </span>
                </div>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1.1fr)_minmax(340px,0.9fr)]">
            <div class="panel px-4 py-4">
                <div class="flex flex-wrap items-start justify-between gap-3">
                    <div>
                        <div class="eyebrow">Budget Health</div>
                        <h2 class="mt-1 text-xl font-bold text-gray-950 dark:text-slate-50">Spending pressure by budget</h2>
                        <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">{{ $budgetCount }} active budgets tracked.</p>
                    </div>
                    <div class="rounded-lg bg-gray-50 px-3 py-2 text-right ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Avg Spend</div>
                        <div class="font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($averageTransaction) }}</div>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($budgetHealth as $budget)
                        <div wire:key="dashboard-budget-health-{{ $budget['id'] ?? str($budget['name'])->slug() }}">
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $budget['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">{{ $this->rupiah($budget['spent']) }} of {{ $this->rupiah($budget['income']) }}</div>
                                </div>
                                <div class="{{ $budget['tone'] === 'danger' ? 'text-red-500' : ($budget['tone'] === 'warning' ? 'text-amber-500' : 'text-green-500') }} shrink-0 font-semibold">
                                    {{ $budget['percentage'] }}%
                                </div>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                                <div class="h-full rounded-full {{ $budget['tone'] === 'danger' ? 'bg-red-500' : ($budget['tone'] === 'warning' ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ $budget['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">
                            No budgets yet.
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="eyebrow">Recent Movement</div>
                        <h2 class="mt-1 text-xl font-bold text-gray-950 dark:text-slate-50">Latest expenses</h2>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6l4 2m5-2a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </span>
                </div>

                <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($recentExpenses as $expense)
                        <div wire:key="dashboard-recent-expense-{{ $expense->id }}" class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                    <span>{{ $expense->budget?->name }}</span>
                                    <span>{{ $expense->label?->name ?? 'Unlabeled' }}</span>
                                    <span>{{ $expense->created_at?->format('d M Y') }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No recent expenses.</div>
                    @endforelse
                </div>

                @if ($largestExpense)
                    <div class="mt-4 rounded-lg bg-gray-50 px-3 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Largest Expense</div>
                        <div class="mt-2 flex items-center justify-between gap-3">
                            <div class="min-w-0 truncate font-semibold text-gray-950 dark:text-slate-50">{{ $largestExpense->name }}</div>
                            <div class="shrink-0 font-bold text-red-500">{{ $this->rupiah($largestExpense->getRawOriginal('amount')) }}</div>
                        </div>
                    </div>
                @endif
            </div>
        </section>

        <section class="flex flex-wrap items-center justify-between gap-3">
            <div>
                <h2 class="text-xl font-bold text-gray-950 dark:text-slate-50">Breakdown by Label</h2>
                <p class="text-sm text-gray-500 dark:text-slate-400">{{ $labelCount }} active labels, top category: {{ $topLabel['name'] ?? '-' }}.</p>
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
                <article wire:key="dashboard-label-breakdown-{{ str($label['name'])->slug() }}" class="panel px-4 py-4">
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
                            <div wire:key="dashboard-label-{{ str($label['name'])->slug() }}-item-{{ str($item['name'])->slug() }}">
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
