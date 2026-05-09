<div class="min-w-0">
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Spends</div>
                <h1 class="page-title">Transaction Explorer</h1>
                <p class="page-subtitle">Search, filter, and inspect every expense across your budgets.</p>
            </div>

            <a href="{{ route('budgets') }}#expenses" class="btn-primary">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>Add Expense</span>
            </a>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4">
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Filtered Total</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($totalAmount) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Transactions</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ number_format($transactionCount, 0, ',', '.') }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Average Spend</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($averageAmount) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Largest Spend</div>
                <div class="mt-4 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($largestAmount) }}</div>
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

                <select wire:model.live="budgetId" class="input-field">
                    <option value="all">All budgets</option>
                    @foreach ($budgets as $budget)
                        <option value="{{ $budget->id }}">{{ $budget->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="labelId" @disabled(! $labelsReady) class="input-field">
                    <option value="all">All labels</option>
                    @if ($labelsReady)
                        <option value="unlabeled">Unlabeled</option>
                        @foreach ($labels as $label)
                            <option value="{{ $label->id }}">{{ $label->name }}</option>
                        @endforeach
                    @endif
                </select>

                <select wire:model.live="platformId" class="input-field">
                    <option value="all">All platforms</option>
                    @foreach ($platforms as $platform)
                        <option value="{{ $platform->id }}">{{ $platform->name }}</option>
                    @endforeach
                </select>

                <select wire:model.live="sort" class="input-field">
                    <option value="latest">Latest first</option>
                    <option value="amount_desc">Highest amount</option>
                    <option value="amount_asc">Lowest amount</option>
                    <option value="name">Name</option>
                </select>
            </div>

            <div class="mt-3">
                <select wire:model.live="statusId" class="input-field w-full md:w-64">
                    <option value="all">All statuses</option>
                    @foreach ($statuses as $status)
                        <option value="{{ $status->id }}">{{ $status->body }}</option>
                    @endforeach
                </select>
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
                        <tr class="bg-white text-gray-800 transition hover:bg-gray-50 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800/60">
                            <td class="p-3 text-gray-500 dark:text-slate-400">{{ $spend->created_at?->format('d M Y') }}</td>
                            <td class="p-3">
                                <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $spend->name }}</div>
                            </td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->budget?->name }}</td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->label?->name ?? 'Unlabeled' }}</td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->platform?->name }}</td>
                            <td class="p-3 text-gray-600 dark:text-slate-300">{{ $spend->status?->body }}</td>
                            <td class="p-3 text-right font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($spend->getRawOriginal('amount')) }}</td>
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
            {{ $spends->links() }}
        </div>
    </main>
</div>
