<div class="min-w-0" x-data="{deleteMovement: false}">
    <header class="app-header">
        <div class="page-header-layout">
            <div class="page-header-copy">
                <span class="page-hero-icon page-hero-icon-sky">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-6">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22" />
                        <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 6.75 6.11 2.067-2.067 6.11" />
                    </svg>
                </span>

                <div class="min-w-0">
                    <div class="eyebrow">Investment</div>
                    <h1 class="page-title">Investment Ledger</h1>
                </div>
            </div>
        </div>
    </header>

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        @unless ($schemaReady && $targetsReady)
            <section class="rounded-lg border border-amber-200 bg-amber-50 px-4 py-3 text-sm text-amber-800 shadow-sm dark:border-amber-900/60 dark:bg-amber-950/30 dark:text-amber-200">
                Investment ledger is not migrated yet. Run <span class="font-semibold">php artisan migrate</span> to activate this menu.
            </section>
        @endunless

        <section class="summary-grid">
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Original Investment</div>
                        <div class="metric-value money-value">{{ $this->rupiah($summary['principal']) }}</div>
                    </div>
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="m15.75 6.75 6.11 2.067-2.067 6.11" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Withdrawn</div>
                        <div class="metric-value money-value">{{ $this->rupiah($summary['withdrawn']) }}</div>
                    </div>
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m0 0-5.25-5.25M12 19.5l5.25-5.25" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Top Up</div>
                        <div class="metric-value money-value">{{ $this->rupiah($summary['deposit']) }}</div>
                    </div>
                    <span class="icon-box">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 19.5v-15m0 0L6.75 9.75M12 4.5l5.25 5.25" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Current Balance</div>
                        <div class="metric-value money-value">{{ $this->rupiah($summary['balance']) }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12m18 0v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V12m18 0V8.25A2.25 2.25 0 0 0 18.75 6H5.25A2.25 2.25 0 0 0 3 8.25V12" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-start justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Target</div>
                        <div class="metric-value money-value">{{ $this->rupiah($summary['target']) }}</div>
                    </div>
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.59 14.37a6 6 0 0 1-5.84 7.38v-4.8m5.84-2.58a14.98 14.98 0 0 0 6.16-12.12A14.98 14.98 0 0 0 9.63 8.41m5.96 5.96a14.98 14.98 0 0 1-5.96 2.58m0-8.54a6 6 0 0 0-7.38 5.84h4.8m2.58-5.84a14.98 14.98 0 0 0-2.58 5.84m2.58 2.7L7.05 14.25" />
                        </svg>
                    </span>
                </div>
            </div>
            <div class="metric-card">
                <div class="flex items-center justify-between gap-3">
                    <div class="min-w-0">
                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Target Progress</div>
                        <div class="mt-1 text-sm font-medium text-gray-500 dark:text-slate-400">Overall target</div>
                    </div>
                    <span class="progress-circle size-14" style="--progress: {{ $summary['progressWidth'] }}; --progress-color: #22c55e">
                        <span class="progress-circle-value">{{ $summary['progress'] === null ? '0' : min(999, $summary['progress']) }}%</span>
                    </span>
                </div>
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
                                        {{ $group['transactions'] }} spends / {{ $group['budgets'] }} plans / {{ $group['movements'] }} movements
                                    </div>
                                </div>
                                <div class="shrink-0 text-right">
                                    <div class="money-value text-sm font-bold {{ $group['balance'] < 0 ? 'text-red-500' : 'text-gray-950 dark:text-slate-50' }}">{{ $this->rupiah($group['balance']) }}</div>
                                    <div class="mt-1 text-xs text-gray-400 dark:text-slate-500">balance</div>
                                </div>
                            </div>
                            <div class="mt-3 flex items-center justify-between gap-3">
                                <div class="min-w-0 text-xs">
                                    <div class="text-gray-500 dark:text-slate-400">Target</div>
                                    <div class="money-value mt-0.5 truncate font-medium text-gray-500 dark:text-slate-400">{{ $group['target'] > 0 ? $this->rupiah($group['target']) : 'Not set' }}</div>
                                </div>
                                <span class="progress-circle progress-circle-compact size-10" style="--progress: {{ $group['targetProgressWidth'] }}; --progress-color: {{ $group['target'] > 0 ? '#22c55e' : '#94a3b8' }}">
                                    <span class="progress-circle-value">{{ $group['target'] > 0 ? min(999, $group['targetProgress']) : '0' }}%</span>
                                </span>
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
                            <div class="grid w-full min-w-0 grid-cols-2 gap-2 text-left text-xs sm:w-auto sm:grid-cols-4 sm:text-right">
                                <div>
                                    <div class="text-gray-400 dark:text-slate-500">Original</div>
                                    <div class="money-value mt-1 truncate font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($selected['principal']) }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-400 dark:text-slate-500">Used</div>
                                    <div class="money-value mt-1 truncate font-bold text-red-500">{{ $this->rupiah($selected['withdrawn']) }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-400 dark:text-slate-500">Balance</div>
                                    <div class="money-value mt-1 truncate font-bold {{ $selected['balance'] < 0 ? 'text-red-500' : 'text-green-500' }}">{{ $this->rupiah($selected['balance']) }}</div>
                                </div>
                                <div>
                                    <div class="text-gray-400 dark:text-slate-500">Target</div>
                                    <div class="money-value mt-1 truncate font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($selected['target']) }}</div>
                                </div>
                            </div>
                        @endif
                    </div>

                    @if ($selected)
                        <div class="mt-4 rounded-lg bg-gray-50/80 px-3 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                            <div class="flex flex-wrap items-center justify-between gap-3">
                                <div class="flex min-w-0 items-center gap-3">
                                    <span class="progress-circle size-16" style="--progress: {{ $selected['targetProgressWidth'] }}; --progress-color: #22c55e">
                                        <span class="progress-circle-value">{{ $selected['targetProgress'] === null ? '0' : min(999, $selected['targetProgress']) }}%</span>
                                    </span>
                                    <div class="min-w-0">
                                        <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Target progress</div>
                                        <div class="mt-1 text-sm font-semibold text-gray-950 dark:text-slate-50">
                                            {{ $selected['targetProgress'] === null ? 'Set a target to track progress' : 'Tracking selected target' }}
                                        </div>
                                        @if ($selected['target'] > 0)
                                            <div class="money-value mt-1 text-xs text-gray-500 dark:text-slate-400">{{ $this->rupiah($selected['remainingToTarget']) }} left to target</div>
                                        @endif
                                    </div>
                                </div>
                                <form wire:submit="saveTarget" class="flex w-full min-w-0 gap-2 sm:w-auto">
                                    <input wire:model="targetAmount" data-number-format="live" type="text" inputmode="numeric" placeholder="Target" @disabled(! $targetsReady) class="input-field w-full sm:w-40">
                                    <button type="submit" @disabled(! $targetsReady) class="btn-secondary shrink-0 px-3 py-2 text-xs">Save</button>
                                </form>
                            </div>
                            @error('targetAmount')
                                <div class="mt-2 text-xs text-red-500">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif

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
                    <table class="w-full min-w-[390px] table-fixed sm:min-w-[560px] lg:min-w-[620px]">
                        <colgroup>
                            <col class="w-[4.25rem] sm:w-28">
                            <col class="w-[3.75rem] sm:w-28">
                            <col class="w-[5.25rem] sm:w-[10rem] lg:w-[13rem]">
                            <col class="w-[6.5rem] sm:w-32">
                            <col class="w-10 sm:w-20">
                        </colgroup>
                        <thead class="bg-gray-50 text-[10px] font-semibold uppercase text-gray-500 dark:bg-slate-950 dark:text-slate-400 sm:text-xs">
                            <tr>
                                <th class="px-2 py-2 text-left sm:p-3">Date</th>
                                <th class="px-2 py-2 text-left sm:p-3">Type</th>
                                <th class="px-2 py-2 text-left sm:p-3">Note</th>
                                <th class="px-2 py-2 text-right sm:p-3">Amount</th>
                                <th class="px-1.5 py-2 text-right sm:p-3">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100 text-[13px] dark:divide-slate-800 sm:text-sm">
                            @forelse ($movements as $movement)
                                <tr wire:key="investment-movement-{{ $movement->id }}" class="bg-white text-gray-800 transition hover:bg-gray-50 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800/60">
                                    <td class="whitespace-nowrap px-2 py-2 text-xs text-gray-500 dark:text-slate-400 sm:p-3 sm:text-sm">
                                        <span class="sm:hidden">{{ $movement->occurred_on?->format('d/m/y') }}</span>
                                        <span class="hidden sm:inline">{{ $movement->occurred_on?->format('d M Y') }}</span>
                                    </td>
                                    <td class="px-2 py-2 sm:p-3">
                                        <span class="inline-flex rounded-md px-1.5 py-0.5 text-[11px] font-semibold sm:px-2 sm:py-1 sm:text-xs {{ $movement->type === 'withdrawal' ? 'bg-red-50 text-red-600 dark:bg-red-500/10 dark:text-red-300' : 'bg-green-50 text-green-600 dark:bg-green-500/10 dark:text-green-300' }}">
                                            <span class="sm:hidden">{{ $movement->type === 'withdrawal' ? 'Out' : 'In' }}</span>
                                            <span class="hidden sm:inline">{{ $movement->type === 'withdrawal' ? 'Withdrawal' : 'Top up' }}</span>
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 text-gray-600 dark:text-slate-300 sm:p-3">
                                        <div class="max-w-[4.5rem] truncate sm:max-w-[9rem] lg:max-w-[12rem]" title="{{ $movement->note ?? '-' }}">{{ $movement->note ?? '-' }}</div>
                                    </td>
                                    <td class="money-value whitespace-nowrap px-2 py-2 text-right font-bold sm:p-3 {{ $movement->type === 'withdrawal' ? 'text-red-500' : 'text-green-500' }}">{{ $this->rupiah($movement->amount) }}</td>
                                    <td class="px-1.5 py-2 text-right sm:p-3">
                                        <button type="button" x-on:click="deleteMovement = true" wire:click="confirmDeleteMovement({{ $movement->id }})" class="btn-secondary size-8 px-0 py-0 text-xs sm:h-auto sm:w-auto sm:px-3 sm:py-1.5" aria-label="Delete movement" data-tooltip="Delete movement">
                                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-4 sm:hidden">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a49.058 49.058 0 0 0-7.5 0" />
                                            </svg>
                                            <span class="hidden sm:inline">Delete</span>
                                        </button>
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
