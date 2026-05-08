<div
    x-data="{createBudget: false, selectBudget: false, createExpense: false, renameBudget: false, deleteBudget: false}"
    x-on:saved="createExpense = false"
    x-on:budget-created="createBudget = false; selectBudget = false"
    x-on:budget-renamed="renameBudget = false"
    x-on:budget-deleted="deleteBudget = false"
    class="min-w-0"
>
    <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 bg-white px-6 py-4 text-sm dark:border-slate-800 dark:bg-slate-950 lg:px-8">
        <div>
            <div class="text-xs font-medium uppercase text-gray-400 dark:text-slate-500">Active Budget</div>
            <h1 class="text-2xl font-bold text-gray-900 dark:text-slate-100">{{ $activeBudget?->name ?? 'No budget yet' }}</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
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

            @if ($activeBudget)
                <div class="relative min-w-44">
                    <button x-on:click="selectBudget = true" class="flex w-full items-center justify-between rounded-lg bg-gray-100 px-3 py-2 text-gray-700 dark:bg-slate-900 dark:text-slate-200">
                        <span class="truncate">{{ $activeBudget->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div x-show="selectBudget" x-cloak x-on:click.away="selectBudget = false" x-transition class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200 dark:bg-slate-900 dark:ring-slate-700">
                        @foreach ($budgets as $budget)
                            <button x-on:click="selectBudget = false" wire:click="selectBudget({{ $budget->id }})" class="w-full px-3 py-2 text-left text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                {{ $budget->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <button
                    type="button"
                    x-on:click="renameBudget = true"
                    wire:click="startRenamingBudget"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white"
                    aria-label="Rename budget"
                    title="Rename budget"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L6.832 19.82a4.5 4.5 0 0 1-1.897 1.13l-2.685.8.8-2.685a4.5 4.5 0 0 1 1.13-1.897L16.862 4.487Zm0 0L19.5 7.125" />
                    </svg>
                </button>

                <button
                    type="button"
                    wire:click="duplicateActiveBudget"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900 dark:border-slate-700 dark:text-slate-300 dark:hover:bg-slate-900 dark:hover:text-white"
                    aria-label="Duplicate budget"
                    title="Duplicate budget"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M15.75 17.25v3.375c0 .621-.504 1.125-1.125 1.125h-9.75a1.125 1.125 0 0 1-1.125-1.125v-9.75c0-.621.504-1.125 1.125-1.125H8.25m7.5 7.5h3.375c.621 0 1.125-.504 1.125-1.125v-9.75c0-.621-.504-1.125-1.125-1.125h-9.75A1.125 1.125 0 0 0 8.25 6.375v3.375m7.5 7.5H9.375A1.125 1.125 0 0 1 8.25 16.125V9.75" />
                    </svg>
                </button>

                <button
                    type="button"
                    x-on:click="deleteBudget = true"
                    class="inline-flex items-center justify-center rounded-lg border border-red-200 p-2 text-red-500 hover:bg-red-50 dark:border-red-900/70 dark:hover:bg-red-950/40"
                    aria-label="Delete budget"
                    title="Delete budget"
                >
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                        <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a49.058 49.058 0 0 0-7.5 0" />
                    </svg>
                </button>
            @endif

            <button x-on:click="createBudget = true" class="flex items-center gap-2 rounded-lg bg-green-500 px-3 py-2 font-semibold text-white hover:bg-green-600">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                </svg>
                <span>New Budget</span>
            </button>
        </div>
    </header>

    <livewire:create-budget />

    @if ($activeBudget)
        <div x-show="renameBudget" x-cloak x-transition class="fixed left-0 top-0 z-50 flex h-full w-full bg-black bg-opacity-10 backdrop-blur-sm dark:bg-black/40">
            <div x-on:click.away="renameBudget = false" class="m-auto w-80 rounded-lg bg-white px-8 py-8 dark:bg-slate-900">
                <div class="text-lg font-semibold text-gray-900 dark:text-slate-100">Rename Budget</div>

                <form class="mt-5 space-y-4" wire:submit="renameActiveBudget">
                    <div>
                        <input
                            wire:model="renameBudgetName"
                            type="text"
                            class="w-full rounded-lg bg-gray-100 px-3 py-2 dark:bg-slate-800 dark:text-slate-100"
                            placeholder="Budget name"
                        >
                        @error('renameBudgetName')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" x-on:click="renameBudget = false" class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800">
                            Cancel
                        </button>
                        <button type="submit" class="rounded-lg bg-green-500 px-3 py-2 text-sm font-semibold text-white hover:bg-green-600">
                            Save
                        </button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if ($activeBudget)
        <div x-show="deleteBudget" x-cloak x-transition class="fixed left-0 top-0 z-50 flex h-full w-full bg-black bg-opacity-10 backdrop-blur-sm dark:bg-black/40">
            <div x-on:click.away="deleteBudget = false" class="m-auto w-80 rounded-lg bg-white px-8 py-8 dark:bg-slate-900">
                <div class="text-lg font-semibold text-gray-900 dark:text-slate-100">Delete Budget</div>
                <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">
                    Delete <span class="font-semibold text-gray-900 dark:text-slate-100">{{ $activeBudget->name }}</span> and all its expenses? This action cannot be undone.
                </p>

                <form class="mt-6 flex justify-end gap-2" wire:submit="deleteActiveBudget">
                    <button type="button" x-on:click="deleteBudget = false" class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100 dark:text-slate-400 dark:hover:bg-slate-800">
                        Cancel
                    </button>
                    <button type="submit" class="rounded-lg bg-red-500 px-3 py-2 text-sm font-semibold text-white hover:bg-red-600">
                        Delete
                    </button>
                </form>
            </div>
        </div>
    @endif

    <main class="space-y-6 px-6 py-6 lg:px-8">
        @if ($activeBudget)
            <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-5">
                @foreach ($summaryCards as $card)
                    <div class="min-h-28 rounded-lg border border-gray-200 bg-white px-4 py-3 text-gray-800 dark:border-slate-800 dark:bg-slate-900 dark:text-slate-100">
                        <div class="text-xs font-medium text-gray-400 dark:text-slate-500">{{ $card['label'] }}</div>
                        <div class="mt-5 truncate text-xl font-bold">{{ $this->rupiah($card['amount']) }}</div>
                    </div>
                @endforeach
            </section>

            <section id="reports" class="grid gap-4 xl:grid-cols-[minmax(0,1.15fr)_minmax(320px,0.85fr)]">
                <div class="rounded-lg border border-gray-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900">
                    <div class="flex items-center justify-between gap-3">
                        <div>
                            <h2 class="text-base font-bold text-gray-900 dark:text-slate-100">Budget Pulse</h2>
                            <p class="text-xs text-gray-500 dark:text-slate-400">{{ $spendProgress }}% used</p>
                        </div>
                        <div class="{{ $remainingBalance < 0 ? 'text-red-500' : 'text-green-500' }} text-sm font-semibold">
                            {{ $this->rupiah($remainingBalance) }}
                        </div>
                    </div>
                    <div class="mt-4 h-3 overflow-hidden rounded-full bg-gray-100 dark:bg-slate-800">
                        <div class="h-full rounded-full {{ $remainingBalance < 0 ? 'bg-red-500' : 'bg-green-500' }}" style="width: {{ $spendProgress }}%"></div>
                    </div>
                </div>

                <div class="rounded-lg border border-gray-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900">
                    <h2 class="text-base font-bold text-gray-900 dark:text-slate-100">Status</h2>
                    <div class="mt-3 grid gap-2 sm:grid-cols-2">
                        @forelse ($statusAnalytics as $status)
                            <div class="rounded-lg bg-gray-50 px-3 py-2 dark:bg-slate-800">
                                <div class="flex items-center justify-between gap-2 text-sm">
                                    <span class="truncate font-semibold text-gray-700 dark:text-slate-200">{{ ucfirst($status['name']) }}</span>
                                    <span class="text-gray-500 dark:text-slate-400">{{ $status['transactions'] }}x</span>
                                </div>
                                <div class="mt-2 text-sm font-bold text-gray-900 dark:text-slate-100">{{ $this->rupiah($status['total']) }}</div>
                            </div>
                        @empty
                            <div class="text-sm text-gray-500 dark:text-slate-400">No transactions yet.</div>
                        @endforelse
                    </div>
                </div>
            </section>

            <section class="rounded-lg border border-gray-200 bg-white px-4 py-4 dark:border-slate-800 dark:bg-slate-900">
                <h2 class="text-base font-bold text-gray-900 dark:text-slate-100">Platform Distribution</h2>
                <div class="mt-4 space-y-3">
                    @forelse ($platformAnalytics as $platform)
                        <div>
                            <div class="mb-1 flex items-center justify-between gap-3 text-sm">
                                <span class="font-semibold text-gray-700 dark:text-slate-200">{{ $platform['name'] }}</span>
                                <span class="text-gray-500 dark:text-slate-400">{{ $this->rupiah($platform['total']) }} / {{ $platform['percentage'] }}%</span>
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
                    <h2 class="text-xl font-bold text-gray-900 dark:text-slate-100">Expenses</h2>

                    <button x-on:click="createExpense = true" class="flex items-center gap-2 rounded-lg bg-green-500 px-3 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>New Expense</span>
                    </button>
                </div>

                <livewire:show-expense :activeBudget="$activeBudget" :key="'expenses-'.$activeBudget->id" />
            </section>

            <livewire:create-expense @saved="$refresh" :activeBudget="$activeBudget" :key="'create-expense-'.$activeBudget->id" />
        @else
            <section class="rounded-lg border border-dashed border-gray-300 px-6 py-12 text-center dark:border-slate-700">
                <div class="text-lg font-semibold text-gray-900 dark:text-slate-100">No budget yet</div>
                <p class="mt-1 text-sm text-gray-500 dark:text-slate-400">Create your first budget to start tracking expenses.</p>
                <button x-on:click="createBudget = true" class="mt-4 rounded-lg bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                    New Budget
                </button>
            </section>
        @endif
    </main>
</div>
