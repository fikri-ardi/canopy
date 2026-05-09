<div class="min-w-0">
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Reports</div>
                <h1 class="page-title">Spending Reports</h1>
                <p class="page-subtitle">Analyze budget health, category pressure, payment mix, and high-impact expenses.</p>
            </div>

            <div class="flex flex-wrap items-center gap-2">
                <select wire:model.live="budgetId" class="input-field w-44">
                    <option value="all">All budgets</option>
                    @foreach ($budgets as $budget)
                        <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                    @endforeach
                </select>
                <select wire:model.live="range" class="input-field w-40">
                    <option value="all">All time</option>
                    <option value="30">Last 30 days</option>
                    <option value="90">Last 90 days</option>
                    <option value="365">Last year</option>
                </select>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Planned Income</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($totalIncome) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Expense</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($totalExpense) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Remaining</div>
                <div class="mt-4 truncate text-xl font-bold {{ $remainingBalance < 0 ? 'text-red-500' : 'text-gray-950 dark:text-slate-50' }}">{{ $this->rupiah($remainingBalance) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Transactions</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ number_format($transactionCount, 0, ',', '.') }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Average</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($averageExpense) }}</div>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1.1fr)_minmax(340px,0.9fr)]">
            <div class="panel px-4 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="eyebrow">Budget Progress</div>
                        <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Income used per budget</h2>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($budgetProgress as $budget)
                        <div>
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $budget['name'] }}</div>
                                    <div class="text-xs text-gray-500 dark:text-slate-400">{{ $this->rupiah($budget['spent']) }} of {{ $this->rupiah($budget['income']) }}</div>
                                </div>
                                <div class="{{ $budget['remaining'] < 0 ? 'text-red-500' : ($budget['percentage'] >= 80 ? 'text-amber-500' : 'text-green-500') }} shrink-0 font-semibold">
                                    {{ $budget['percentage'] }}%
                                </div>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                                <div class="h-full rounded-full {{ $budget['remaining'] < 0 ? 'bg-red-500' : ($budget['percentage'] >= 80 ? 'bg-amber-500' : 'bg-green-500') }}" style="width: {{ $budget['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">No budgets yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Top Expenses</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Highest-impact transactions</h2>

                <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($topExpenses as $expense)
                        <div class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                    <span>{{ $expense->budget?->name }}</span>
                                    <span>{{ $expense->label?->name ?? 'Unlabeled' }}</span>
                                </div>
                            </div>
                            <div class="shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No expenses in this report.</div>
                    @endforelse
                </div>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-3">
            <div class="panel px-4 py-4">
                <div class="eyebrow">Labels</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Spending by label</h2>

                <div class="mt-4 space-y-3">
                    @forelse ($labelBreakdown as $label)
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $label['name'] }}</span>
                                <span class="shrink-0 text-gray-500 dark:text-slate-400">{{ $this->rupiah($label['total']) }}</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                                <div class="h-full rounded-full bg-green-500" style="width: {{ $label['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">
                            {{ $labelsReady ? 'No label spending yet.' : 'Run label migration to activate label reports.' }}
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Platforms</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Payment mix</h2>

                <div class="mt-4 space-y-3">
                    @forelse ($platformBreakdown as $platform)
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $platform['name'] }}</span>
                                <span class="shrink-0 text-gray-500 dark:text-slate-400">{{ $platform['percentage'] }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                                <div class="h-full rounded-full bg-green-500" style="width: {{ $platform['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No platform data yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Status</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Allocation state</h2>

                <div class="mt-4 grid gap-2">
                    @forelse ($statusBreakdown as $status)
                        <div class="rounded-lg bg-gray-50 px-3 py-2 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                            <div class="flex items-center justify-between gap-2 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ ucfirst($status['name']) }}</span>
                                <span class="text-gray-500 dark:text-slate-400">{{ $status['transactions'] }}x</span>
                            </div>
                            <div class="mt-2 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($status['total']) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No status data yet.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
</div>
