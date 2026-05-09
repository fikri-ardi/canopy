<div
    x-data="{createBudget: false, selectBudget: false, createExpense: false, renameBudget: false, deleteBudget: false}"
    x-on:saved="createExpense = false"
    x-on:budget-created="createBudget = false; selectBudget = false"
    x-on:budget-renamed="renameBudget = false"
    x-on:budget-deleted="deleteBudget = false"
    class="min-w-0"
>
    <header class="app-header">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <div class="eyebrow">Active Budget</div>
                <h1 class="page-title">{{ $activeBudget?->name ?? 'No budget yet' }}</h1>
                <p class="page-subtitle">Track income, allocations, and spending health in one place.</p>
            </div>

            <div class="flex w-full flex-wrap items-center gap-2 sm:w-auto">
                <button
                    type="button"
                    x-on:click="theme = theme === 'dark' ? 'light' : 'dark'"
                    class="btn-icon"
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

                @if ($activeBudget)
                    <div class="relative min-w-0 flex-1 sm:min-w-48 sm:flex-none">
                        <button type="button" x-on:click="selectBudget = true" class="btn-secondary w-full justify-between">
                            <span class="truncate">{{ $activeBudget->name }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="selectBudget" x-cloak x-on:click.away="selectBudget = false" x-transition class="select-menu">
                            @foreach ($budgets as $budget)
                                <button type="button" x-on:click="selectBudget = false" wire:click="selectBudget({{ $budget->id }})" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                    {{ $budget->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>

                    <button type="button" x-on:click="renameBudget = true" wire:click="startRenamingBudget" class="btn-icon" aria-label="Rename budget" title="Rename budget">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125" />
                        </svg>
                    </button>

                    <button type="button" wire:click="duplicateActiveBudget" class="btn-icon" aria-label="Duplicate budget" title="Duplicate budget">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125v-9.75c0-.621.504-1.125 1.125-1.125H8.25m7.5 7.5h3.375c.621 0 1.125-.504 1.125-1.125v-9.75c0-.621-.504-1.125-1.125-1.125h-9.75A1.125 1.125 0 0 0 8.25 6.375v3.375m7.5 7.5H9.375A1.125 1.125 0 0 1 8.25 16.125V9.75" />
                        </svg>
                    </button>

                    <button type="button" x-on:click="deleteBudget = true" class="btn-icon border-red-200 text-red-500 hover:bg-red-50 hover:text-red-600 dark:border-red-900/70 dark:hover:bg-red-950/40" aria-label="Delete budget" title="Delete budget">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a49.058 49.058 0 0 0-7.5 0" />
                        </svg>
                    </button>
                @endif

                <button type="button" x-on:click="createBudget = true" class="btn-primary">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                    </svg>
                    <span>New Budget</span>
                </button>
            </div>
        </div>
    </header>

    <livewire:create-budget />

    @if ($activeBudget)
        <div x-show="renameBudget" x-cloak x-transition class="modal-backdrop">
            <div x-on:click.away="renameBudget = false" class="modal-panel">
                <div class="flex items-center gap-3">
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Z" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Rename Budget</div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Give this plan a clearer name.</p>
                    </div>
                </div>

                <form class="mt-5 space-y-4" wire:submit="renameActiveBudget">
                    <div>
                        <input wire:model="renameBudgetName" type="text" class="input-field" placeholder="Budget name">
                        @error('renameBudgetName')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" x-on:click="renameBudget = false" class="btn-secondary">Cancel</button>
                        <button type="submit" class="btn-primary">Save</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($activeBudget)
        <div x-show="deleteBudget" x-cloak x-transition class="modal-backdrop">
            <div x-on:click.away="deleteBudget = false" class="modal-panel">
                <div class="flex items-center gap-3">
                    <span class="inline-flex size-10 shrink-0 items-center justify-center rounded-lg bg-red-50 text-red-600 ring-1 ring-red-100 dark:bg-red-500/10 dark:text-red-300 dark:ring-red-500/20">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166M19.228 5.79 18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79" />
                        </svg>
                    </span>
                    <div>
                        <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Delete Budget</div>
                        <p class="text-sm text-gray-500 dark:text-slate-400">This action cannot be undone.</p>
                    </div>
                </div>

                <p class="mt-5 text-sm text-gray-600 dark:text-slate-300">
                    Delete <span class="font-semibold text-gray-950 dark:text-slate-50">{{ $activeBudget->name }}</span> and all its expenses?
                </p>

                <form class="mt-6 flex justify-end gap-2" wire:submit="deleteActiveBudget">
                    <button type="button" x-on:click="deleteBudget = false" class="btn-secondary">Cancel</button>
                    <button type="submit" class="btn-danger">Delete</button>
                </form>
            </div>
        </div>
    @endif

    <main class="space-y-6 px-4 py-5 sm:px-6 sm:py-6 lg:px-8">
        @if ($activeBudget)
            <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($summaryCards as $card)
                    <div class="metric-card">
                        <div class="flex items-start justify-between gap-3">
                            <div class="min-w-0">
                                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">{{ $card['label'] }}</div>
                                <div class="mt-5 truncate text-xl font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($card['amount']) }}</div>
                            </div>
                            <span class="icon-box">
                                @switch($card['label'])
                                    @case('TOTAL INCOME')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M12 6v12m-3-2.818.879.659c1.171.879 3.07.879 4.242 0 1.172-.879 1.172-2.303 0-3.182-.586-.439-1.354-.659-2.121-.659-.768 0-1.536-.22-2.121-.659-1.172-.879-1.172-2.303 0-3.182 1.171-.879 3.07-.879 4.242 0l.879.659" /></svg>
                                        @break
                                    @case('TOTAL EXPENSE')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M2.25 18.75a60.07 60.07 0 0 1 15.797 2.101c.727.198 1.453-.342 1.453-1.096V18.75M3.75 4.5v.75A.75.75 0 0 1 3 6h-.75m0 0v-.375c0-.621.504-1.125 1.125-1.125h17.25c.621 0 1.125.504 1.125 1.125V6m-19.5 0v9m19.5-9v9m0 0v.375c0 .621-.504 1.125-1.125 1.125H3.375A1.125 1.125 0 0 1 2.25 15.375V15m19.5 0h-.75a.75.75 0 0 0-.75.75v.75" /></svg>
                                        @break
                                    @case('REMAINING')
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" /></svg>
                                        @break
                                    @default
                                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5"><path stroke-linecap="round" stroke-linejoin="round" d="M21 12a2.25 2.25 0 0 0-2.25-2.25H5.25A2.25 2.25 0 0 0 3 12m18 0v6.75A2.25 2.25 0 0 1 18.75 21H5.25A2.25 2.25 0 0 1 3 18.75V12m18 0V8.25A2.25 2.25 0 0 0 18.75 6H5.25A2.25 2.25 0 0 0 3 8.25V12" /></svg>
                                @endswitch
                            </span>
                        </div>
                    </div>
                @endforeach
            </section>

            <section class="grid gap-4 xl:grid-cols-[minmax(0,0.9fr)_minmax(340px,1.1fr)]">
                <div class="panel px-4 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="eyebrow">Budget Intelligence</div>
                            <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Current plan signals</h2>
                        </div>
                        <span class="icon-box-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                            </svg>
                        </span>
                    </div>

                    <div class="mt-4 grid gap-3 sm:grid-cols-2">
                        @foreach ($insightCards as $card)
                            <div class="rounded-lg bg-gray-50 px-3 py-3 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                <div class="text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">{{ $card['label'] }}</div>
                                <div class="mt-3 truncate text-lg font-bold text-gray-950 dark:text-slate-50">
                                    {{ $card['format'] === 'money' ? $this->rupiah($card['amount']) : number_format($card['amount'], 0, ',', '.') }}
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <div class="panel px-4 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <div class="eyebrow">Top Expenses</div>
                            <h2 class="mt-1 text-base font-bold text-gray-950 dark:text-slate-50">Largest items in this budget</h2>
                        </div>
                        <div class="{{ $remainingBalance < 0 ? 'text-red-500' : 'text-green-500' }} text-sm font-semibold">
                            {{ $this->rupiah($remainingBalance) }} left
                        </div>
                    </div>

                    <div class="mt-4 divide-y divide-gray-100 dark:divide-slate-800">
                        @forelse ($topExpenses as $expense)
                            <div class="flex items-center justify-between gap-3 py-3">
                                <div class="min-w-0">
                                    <div class="truncate font-semibold text-gray-950 dark:text-slate-50">{{ $expense->name }}</div>
                                    <div class="mt-1 flex flex-wrap items-center gap-2 text-xs text-gray-500 dark:text-slate-400">
                                        <span>{{ $expense->label?->name ?? 'Unlabeled' }}</span>
                                        <span>{{ $expense->platform?->name }}</span>
                                        <span>{{ $expense->status?->body }}</span>
                                    </div>
                                </div>
                                <div class="shrink-0 font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($expense->getRawOriginal('amount')) }}</div>
                            </div>
                        @empty
                            <div class="py-8 text-center text-sm text-gray-500 dark:text-slate-400">No expenses yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section id="reports" class="grid gap-4 xl:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)]">
                <div class="panel px-4 py-4">
                    <div class="flex items-center justify-between gap-3">
                        <div class="flex items-center gap-3">
                            <span class="icon-box">
                                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 13.125C3 12.504 3.504 12 4.125 12h2.25c.621 0 1.125.504 1.125 1.125v6.75C7.5 20.496 6.996 21 6.375 21h-2.25A1.125 1.125 0 0 1 3 19.875v-6.75ZM9.75 8.625c0-.621.504-1.125 1.125-1.125h2.25c.621 0 1.125.504 1.125 1.125v11.25c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V8.625ZM16.5 4.125c0-.621.504-1.125 1.125-1.125h2.25C20.496 3 21 3.504 21 4.125v15.75c0 .621-.504 1.125-1.125 1.125h-2.25a1.125 1.125 0 0 1-1.125-1.125V4.125Z" />
                                </svg>
                            </span>
                            <div>
                                <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Budget Pulse</h2>
                                <p class="text-xs text-gray-500 dark:text-slate-400">{{ $spendProgress }}% used</p>
                            </div>
                        </div>
                        <div class="{{ $remainingBalance < 0 ? 'text-red-500' : 'text-green-500' }} text-sm font-semibold">
                            {{ $this->rupiah($remainingBalance) }}
                        </div>
                    </div>
                    <div class="mt-5 h-3 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                        <div class="h-full rounded-full {{ $remainingBalance < 0 ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ $spendProgress }}%"></div>
                    </div>
                </div>

                <div class="panel px-4 py-4">
                    <div class="flex items-center gap-3">
                        <span class="icon-box-muted">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12c0 1.268-.63 2.39-1.593 3.068a3.745 3.745 0 0 1-1.043 4.296 3.745 3.745 0 0 1-4.296 1.043A3.745 3.745 0 0 1 12 21c-1.268 0-2.39-.63-3.068-1.593a3.745 3.745 0 0 1-4.296-1.043 3.745 3.745 0 0 1-1.043-4.296A3.745 3.745 0 0 1 3 12c0-1.268.63-2.39 1.593-3.068a3.745 3.745 0 0 1 1.043-4.296 3.745 3.745 0 0 1 4.296-1.043A3.745 3.745 0 0 1 12 3c1.268 0 2.39.63 3.068 1.593a3.745 3.745 0 0 1 4.296 1.043 3.745 3.745 0 0 1 1.043 4.296A3.745 3.745 0 0 1 21 12Z" />
                            </svg>
                        </span>
                        <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Status</h2>
                    </div>

                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                        @forelse ($statusAnalytics as $status)
                            <div class="rounded-lg bg-gray-50 px-3 py-2 ring-1 ring-gray-100 dark:bg-slate-800/70 dark:ring-slate-700">
                                <div class="flex items-center justify-between gap-2 text-sm">
                                    <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ ucfirst($status['name']) }}</span>
                                    <span class="text-gray-500 dark:text-slate-400">{{ $status['transactions'] }}x</span>
                                </div>
                                <div class="mt-2 text-sm font-bold text-gray-950 dark:text-slate-50">{{ $this->rupiah($status['total']) }}</div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-slate-400">No transactions yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="panel px-4 py-4">
                <div class="flex items-center gap-3">
                    <span class="icon-box-muted">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 6a7.5 7.5 0 1 0 7.5 7.5h-7.5V6Z" />
                            <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 10.5H21A7.5 7.5 0 0 0 13.5 3v7.5Z" />
                        </svg>
                    </span>
                    <h2 class="text-base font-bold text-gray-950 dark:text-slate-50">Platform Distribution</h2>
                </div>

                <div class="mt-4 space-y-3">
                    @forelse ($platformAnalytics as $platform)
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="font-semibold text-gray-700 dark:text-slate-200">{{ $platform['name'] }}</span>
                                <span class="shrink-0 text-gray-500 dark:text-slate-400">{{ $this->rupiah($platform['total']) }} / {{ $platform['percentage'] }}%</span>
                            </div>
                            <div class="h-2 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                                <div class="h-full rounded-full bg-green-500" style="width: {{ $platform['percentage'] }}%"></div>
                            </div>
                        </div>
                    @empty
                        <div class="text-sm text-gray-500 dark:text-slate-400">No platform data yet.</div>
                    @endforelse
                </div>
            </section>

            <section id="expenses" class="min-w-0">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <div>
                        <h2 class="text-xl font-bold text-gray-950 dark:text-slate-50">Expenses</h2>
                        <p class="text-sm text-gray-500 dark:text-slate-400">Inline edit any transaction, label, platform, or status.</p>
                    </div>

                    <button type="button" x-on:click="createExpense = true" class="btn-primary">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>New Expense</span>
                    </button>
                </div>

                <livewire:show-expense :activeBudgetId="$activeBudgetId" :key="'expenses-'.$budgetRenderKey.'-'.$activeBudgetId" />
            </section>

            <livewire:create-expense @saved="$refresh" :activeBudgetId="$activeBudgetId" :key="'create-expense-'.$budgetRenderKey.'-'.$activeBudgetId" />
        @else
            <section class="panel border-dashed px-6 py-12 text-center">
                <span class="icon-box mx-auto">
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08" />
                    </svg>
                </span>
                <div class="mt-4 text-lg font-semibold text-gray-950 dark:text-slate-50">No budget yet</div>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Create your first budget to start tracking expenses.</p>
                <button type="button" x-on:click="createBudget = true" class="btn-primary mt-4">New Budget</button>
            </section>
        @endif
    </main>
</div>
