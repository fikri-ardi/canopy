<div class="min-w-0" x-data="{budgetMenu: alokasiDropdown(), rangeMenu: alokasiDropdown()}">
    <header class="app-header">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-indigo">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125C16.5 3.504 17.004 3 17.625 3h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Reports</div>
                    <h1 class="page-title">Spending Reports</h1>
                </div>
            </div>

            <div class="page-header-actions">
                <div class="min-w-0 flex-1 sm:w-44 sm:flex-none">
                    <button x-ref="budgetTrigger" type="button" x-on:click.stop="budgetMenu.toggle($refs.budgetTrigger, $refs.budgetMenu)" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $budgetId === 'all' ? 'All plans' : $budgets->firstWhere('id', (int) $budgetId)?->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <template x-teleport="body">
                        <div x-ref="budgetMenu" x-show="budgetMenu.open" x-cloak x-transition x-bind:style="budgetMenu.style" x-on:click.outside="budgetMenu.close()" x-on:resize.window="budgetMenu.close()" wire:key="reports-budget-menu" wire:ignore.self class="floating-select-menu">
                            <button type="button" x-on:click="budgetMenu.close()" wire:click="$set('budgetId', 'all')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">All plans</button>
                            @foreach ($budgets as $budget)
                                <button type="button" x-on:click="budgetMenu.close()" wire:click="$set('budgetId', '{{ $budget->id }}')" wire:key="reports-budget-option-{{ $budget->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $budget->name }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>

                <div class="min-w-0 flex-1 sm:w-40 sm:flex-none">
                    @php($rangeLabels = ['all' => 'All time', '30' => 'Last 30 days', '90' => 'Last 90 days', '365' => 'Last year'])
                    <button x-ref="rangeTrigger" type="button" x-on:click.stop="rangeMenu.toggle($refs.rangeTrigger, $refs.rangeMenu)" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $rangeLabels[$range] ?? 'All time' }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>
                    <template x-teleport="body">
                        <div x-ref="rangeMenu" x-show="rangeMenu.open" x-cloak x-transition x-bind:style="rangeMenu.style" x-on:click.outside="rangeMenu.close()" x-on:resize.window="rangeMenu.close()" wire:key="reports-range-menu" wire:ignore.self class="floating-select-menu">
                            @foreach ($rangeLabels as $value => $label)
                                <button type="button" x-on:click="rangeMenu.close()" wire:click="$set('range', '{{ $value }}')" wire:key="reports-range-option-{{ $value }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">{{ $label }}</button>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="summary-grid">
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Planned Income</div>
                <div class="metric-value money-value">{{ $this->rupiah($totalIncome) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Expense</div>
                <div class="metric-value money-value">{{ $this->rupiah($totalExpense) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Remaining</div>
                <div class="{{ $remainingBalance < 0 ? 'metric-value-danger' : 'metric-value' }} money-value">{{ $this->rupiah($remainingBalance) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Transactions</div>
                <div class="metric-value">{{ number_format($transactionCount, 0, ',', '.') }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Average</div>
                <div class="metric-value money-value">{{ $this->rupiah($averageExpense) }}</div>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(0,1.1fr)_minmax(340px,0.9fr)]">
            <div class="panel px-4 py-4">
                <div class="flex items-center justify-between gap-3">
                    <div>
                        <div class="eyebrow">Plan Progress</div>
                        <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Income used per plan</h2>
                    </div>
                </div>

                <div class="mt-5 space-y-4">
                    @forelse ($budgetProgress as $budget)
                        <div wire:key="reports-budget-progress-{{ $budget['id'] ?? str($budget['name'])->slug() }}">
                            <div class="mb-2 flex items-center justify-between gap-3 text-sm">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $budget['name'] }}</div>
                                    <div class="money-value text-xs text-gray-500 dark:text-slate-400">{{ $this->rupiah($budget['spent']) }} of {{ $this->rupiah($budget['income']) }}</div>
                                </div>
                                <div class="{{ $budget['remaining'] < 0 ? 'text-red-500' : ($budget['percentage'] >= 80 ? 'text-amber-500' : 'text-green-500') }} shrink-0 font-semibold">
                                    {{ $budget['percentage'] }}%
                                </div>
                            </div>
                            <div class="progress-track h-2">
                                <div class="progress-fill" style="--progress: {{ $budget['percentage'] }}%; --progress-color: {{ $budget['remaining'] < 0 ? '#ef4444' : ($budget['percentage'] >= 80 ? '#f59e0b' : '#22c55e') }}"></div>
                            </div>
                        </div>
                    @empty
                        <div class="rounded-lg border border-dashed border-gray-200 px-4 py-8 text-center text-sm text-gray-500 dark:border-slate-700 dark:text-slate-400">No plans yet.</div>
                    @endforelse
                </div>
            </div>

            <div class="panel px-4 py-4">
                <div class="eyebrow">Top Expenses</div>
                <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Highest-impact transactions</h2>

                <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                    @forelse ($topExpenses as $expense)
                        <div wire:key="reports-top-expense-{{ $expense->id }}" class="flex items-center justify-between gap-3 py-3">
                            <div class="min-w-0">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                    <span>{{ $expense->budget?->name }}</span>
                                    <span>{{ $expense->label?->name ?? 'Unlabeled' }}</span>
                                </div>
                            </div>
                            <div class="money-value shrink-0 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
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
                        <div wire:key="reports-label-breakdown-{{ str($label['name'])->slug() }}">
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $label['name'] }}</span>
                                <span class="money-value shrink-0 text-gray-500 dark:text-slate-400">{{ $this->rupiah($label['total']) }}</span>
                            </div>
                            <div class="progress-track h-2">
                                <div class="progress-fill" style="--progress: {{ $label['percentage'] }}%; --progress-color: #22c55e"></div>
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
                        <div wire:key="reports-platform-breakdown-{{ str($platform['name'])->slug() }}">
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ $platform['name'] }}</span>
                                <span class="shrink-0 text-gray-500 dark:text-slate-400">{{ $platform['percentage'] }}%</span>
                            </div>
                            <div class="progress-track h-2">
                                <div class="progress-fill" style="--progress: {{ $platform['percentage'] }}%; --progress-color: #22c55e"></div>
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
                        <div wire:key="reports-status-breakdown-{{ str($status['name'])->slug() }}" class="rounded-lg bg-gray-50 px-3 py-2 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                            <div class="flex items-center justify-between gap-2 text-sm">
                                <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ ucfirst($status['name']) }}</span>
                                <span class="text-gray-500 dark:text-slate-400">{{ $status['transactions'] }}x</span>
                            </div>
                            <div class="money-value mt-2 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($status['total']) }}</div>
                        </div>
                    @empty
                        <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No status data yet.</div>
                    @endforelse
                </div>
            </div>
        </section>
    </main>
</div>
