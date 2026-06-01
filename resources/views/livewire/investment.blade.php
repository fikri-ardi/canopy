<div class="min-w-0" x-data="{deleteMovement: false}">
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Investment</div>
                <h1 class="page-title">Investment Ledger</h1>
                <p class="page-subtitle">Track investment balances without changing the original expense records.</p>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        @unless ($schemaReady)
            <section class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                Investment ledger is not migrated yet. Run <span class="font-semibold">php artisan migrate</span> to activate this menu.
            </section>
        @endunless

        <section class="summary-grid">
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Original Investment</div>
                <div class="metric-value">{{ $this->rupiah($summary['principal']) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Withdrawn</div>
                <div class="metric-value">{{ $this->rupiah($summary['withdrawn']) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Top Up</div>
                <div class="metric-value">{{ $this->rupiah($summary['deposit']) }}</div>
            </div>
            <div class="metric-card">
                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Current Balance</div>
                <div class="metric-value">{{ $this->rupiah($summary['balance']) }}</div>
            </div>
        </section>

        <section class="grid gap-4 xl:grid-cols-[minmax(280px,0.85fr)_minmax(0,1.15fr)]">
            <div class="panel overflow-hidden">
                <div class="border-b border-gray-100 px-4 py-3 dark:border-slate-800">
                    <div class="text-sm font-bold text-gray-950 dark:text-slate-50">Investment Items</div>
                    <p class="mt-1 text-xs text-gray-500 dark:text-slate-400">Grouped from all spends labeled investment.</p>
                </div>

                <div class="max-h-[34rem] divide-y divide-gray-100 overflow-y-auto dark:divide-slate-800">
                    @forelse ($groups as $group)
                        <button type="button" wire:click="selectInvestment(@js($group['key']))" wire:key="investment-item-{{ str($group['key'])->slug() }}" class="block w-full px-4 py-3 text-left transition hover:bg-gray-50 dark:hover:bg-slate-800/70 {{ ($selected['key'] ?? null) === $group['key'] ? 'bg-green-50/70 dark:bg-green-500/10' : 'bg-white dark:bg-slate-900' }}">
                            <div class="flex items-start justify-between gap-3">
                                <div class="min-w-0">
                                    <div class="truncate text-sm font-semibold text-gray-950 dark:text-slate-50">{{ $group['name'] }}</div>
                                    <div class="mt-1 text-xs text-gray-500 dark:text-slate-400">
                                        {{ $group['transactions'] }} spends / {{ $group['budgets'] }} budgets / {{ $group['movements'] }} movements
                                    </div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <div class="text-sm font-bold {{ $group['balance'] < 0 ? 'text-red-500' : 'text-gray-950 dark:text-slate-50' }}">{{ $this->rupiah($group['balance']) }}</div>
                                    <div class="mt-1 text-xs text-gray-400 dark:text-slate-500">balance</div>
                                </div>
                            </div>
                        </button>
                    @empty
                        <div class="px-4 py-12 text-center text-sm">
                            <span class="icon-box mx-auto">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941" />
                                </svg>
                            </span>
                            <div class="mt-3 font-semibold text-gray-950 dark:text-slate-50">No investment spends</div>
                            <p class="mt-1 text-gray-500 dark:text-slate-400">Add spends with label Investment or Investasi first.</p>
                        </div>
                    @endforelse
                </div>
            </div>

            <div class="min-w-0 space-y-4">
                <section class="panel overflow-hidden px-4 py-4">
                    <div class="flex flex-wrap items-start justify-between gap-3">
                        <div class="min-w-0">
                            <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Selected</div>
                            <h2 class="mt-1 truncate text-lg font-bold text-gray-950 dark:text-slate-50">{{ $selected['name'] ?? 'No investment selected' }}</h2>
                        </div>
                        @if ($selected)
                            <div class="grid w-full min-w-0 grid-cols-3 gap-2 text-left text-xs sm:w-auto sm:text-right">
                                <div>
                                    <div class="text-gray-400 dark:text-slate-500">Original</div>
                                    <div class="mt-1 truncate font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($selected['principal']) }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-400 dark:text-slate-500">Used</div>
                                    <div class="mt-1 truncate font-bold text-red-500">{{ $this->rupiah($selected['withdrawn']) }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-400 dark:text-slate-500">Balance</div>
                                    <div class="mt-1 truncate font-bold {{ $selected['balance'] < 0 ? 'text-red-500' : 'text-green-500' }}">{{ $this->rupiah($selected['balance']) }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    <form wire:submit="storeMovement" class="investment-movement-form mt-4 grid min-w-0 max-w-full grid-cols-1 gap-3 sm:grid-cols-2 lg:grid-cols-12">
                        <div class="min-w-0 lg:col-span-3 xl:col-span-2">
                            <select wire:model="movementType" @disabled(! $selected) class="input-field">
                                <option value="withdrawal">Withdrawal</option>
                                <option value="deposit">Top up</option>
                            </select>
                        </div>
                        <div class="min-w-0 lg:col-span-3">
                            <input wire:model="movementAmount" type="text" inputmode="numeric" placeholder="Nominal" @disabled(! $selected) class="input-field">
                        </div>
                        <div class="min-w-0 lg:col-span-3">
                            <input wire:model="movementDate" type="date" @disabled(! $selected) class="input-field">
                        </div>
                        <div class="min-w-0 sm:col-span-2 lg:col-span-3 xl:col-span-4">
                            <input wire:model="movementNote" type="text" placeholder="Note" @disabled(! $selected) class="input-field">
                        </div>
                        <button type="submit" @disabled(! $selected) class="btn-primary w-full min-w-0 sm:col-span-2 lg:col-span-12 lg:w-auto lg:justify-self-end">
                            <span>Save</span>
                        </button>
                    </form>

                    <div class="mt-2 grid gap-1 text-xs text-red-500">
                        @error('movementType') <div>{{ $message }}</div> @enderror
                        @error('movementAmount') <div>{{ $message }}</div> @enderror
                        @error('movementDate') <div>{{ $message }}</div> @enderror
                        @error('movementNote') <div>{{ $message }}</div> @enderror
                    </div>
                </section>

                <section class="table-shell">
                    <table class="w-full min-w-[640px] table-fixed">
                        <thead class="bg-gray-50 text-xs font-semibold uppercase text-gray-500 dark:bg-slate-950 dark:text-slate-400">
                            <tr>
                                <th class="w-28 p-3 text-left">Date</th>
                                <th class="w-28 p-3 text-left">Type</th>
                                <th class="p-3 text-left">Note</th>
                                <th class="w-32 p-3 text-right">Amount</th>
                                <th class="w-20 p-3 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-sm dark:divide-slate-800">
                            @forelse ($movements as $movement)
                                <tr wire:key="investment-movement-{{ $movement->id }}" class="bg-white text-gray-800 transition hover:bg-gray-50 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800/60">
                                    <td class="p-3 text-gray-500 dark:text-slate-400">{{ $movement->occurred_on?->format('d M Y') }}</td>
                                    <td class="p-3">
                                        <span class="inline-flex rounded-md px-2 py-1 text-xs font-semibold {{ $movement->type === 'withdrawal' ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-300' : 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-300' }}">
                                            {{ $movement->type === 'withdrawal' ? 'Withdrawal' : 'Top up' }}
                                        </span>
                                    </td>
                                    <td class="p-3 text-gray-600 dark:text-slate-300">{{ $movement->note ?? '-' }}</td>
                                    <td class="p-3 text-right font-bold {{ $movement->type === 'withdrawal' ? 'text-red-500' : 'text-green-500' }}">{{ $this->rupiah($movement->amount) }}</td>
                                    <td class="p-3 text-right">
                                        <button type="button" x-on:click="deleteMovement = true" wire:click="confirmDeleteMovement({{ $movement->id }})" class="btn-secondary px-3 py-1.5 text-xs">Delete</button>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-10 text-center text-sm text-gray-500 dark:text-slate-400">No movement recorded for this investment.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </section>
            </div>
        </section>
    </main>

    <div x-show="deleteMovement" x-cloak x-transition class="modal-backdrop">
        <div x-on:click.away="deleteMovement = false" class="modal-panel">
            <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Delete Movement</div>
            <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">
                Delete this {{ $deleteMovement?->type === 'withdrawal' ? 'withdrawal' : 'top up' }} record? Original spends will stay unchanged.
            </p>

            <div class="mt-6 flex justify-end gap-2">
                <button type="button" x-on:click="deleteMovement = false" class="btn-secondary">Cancel</button>
                <button type="button" x-on:click="deleteMovement = false" wire:click="deleteMovement" class="btn-danger">Delete</button>
            </div>
        </div>
    </div>
</div>
