<div
    x-data="{createBudget: false, selectBudget: false, createExpense: false, renameBudget: false, deleteBudget: false}"
    x-on:saved="createExpense = false"
    x-on:budget-created="createBudget = false; selectBudget = false"
    x-on:budget-renamed="renameBudget = false"
    x-on:budget-deleted="deleteBudget = false"
    class="min-w-0"
>
    <header class="flex flex-wrap items-center justify-between gap-3 border-b border-gray-200 px-6 py-4 text-sm lg:px-8">
        <div>
            <div class="text-xs font-medium uppercase text-gray-400">Active Budget</div>
            <h1 class="text-2xl font-bold text-gray-900">{{ $activeBudget?->name ?? 'No budget yet' }}</h1>
        </div>

        <div class="flex flex-wrap items-center gap-2">
            @if ($activeBudget)
                <div class="relative min-w-44">
                    <button x-on:click="selectBudget = true" class="flex w-full items-center justify-between rounded-lg bg-gray-100 px-3 py-2 text-gray-700">
                        <span class="truncate">{{ $activeBudget->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div x-show="selectBudget" x-on:click.away="selectBudget = false" x-transition class="absolute left-0 top-full z-50 mt-1 flex w-full flex-col overflow-hidden rounded-lg bg-white shadow-lg ring-1 ring-gray-200">
                        @foreach ($budgets as $budget)
                            <button x-on:click="selectBudget = false" wire:click="selectBudget({{ $budget->id }})" class="w-full px-3 py-2 text-left text-gray-700 hover:bg-gray-100">
                                {{ $budget->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <button
                    type="button"
                    x-on:click="renameBudget = true"
                    wire:click="startRenamingBudget"
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900"
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
                    class="inline-flex items-center justify-center rounded-lg border border-gray-200 p-2 text-gray-600 hover:bg-gray-50 hover:text-gray-900"
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
                    class="inline-flex items-center justify-center rounded-lg border border-red-200 p-2 text-red-500 hover:bg-red-50"
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

    <livewire:createBudget />

    @if ($activeBudget)
        <div x-show="renameBudget" x-transition class="fixed left-0 top-0 z-50 flex h-full w-full bg-black bg-opacity-10 backdrop-blur-sm">
            <div x-on:click.away="renameBudget = false" class="m-auto w-80 rounded-2xl bg-white px-8 py-8">
                <div class="text-lg font-semibold text-gray-900">Rename Budget</div>

                <form class="mt-5 space-y-4" wire:submit="renameActiveBudget">
                    <div>
                        <input
                            wire:model="renameBudgetName"
                            type="text"
                            class="w-full rounded-lg bg-gray-100 px-3 py-2"
                            placeholder="Budget name"
                        >
                        @error('renameBudgetName')
                            <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="flex justify-end gap-2">
                        <button type="button" x-on:click="renameBudget = false" class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100">
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
        <div x-show="deleteBudget" x-transition class="fixed left-0 top-0 z-50 flex h-full w-full bg-black bg-opacity-10 backdrop-blur-sm">
            <div x-on:click.away="deleteBudget = false" class="m-auto w-80 rounded-2xl bg-white px-8 py-8">
                <div class="text-lg font-semibold text-gray-900">Delete Budget</div>
                <p class="mt-3 text-sm text-gray-500">
                    Delete <span class="font-semibold text-gray-900">{{ $activeBudget->name }}</span> and all its expenses? This action cannot be undone.
                </p>

                <form class="mt-6 flex justify-end gap-2" wire:submit="deleteActiveBudget">
                    <button type="button" x-on:click="deleteBudget = false" class="rounded-lg px-3 py-2 text-sm font-semibold text-gray-500 hover:bg-gray-100">
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
            <section class="grid gap-3 sm:grid-cols-2 xl:grid-cols-4 2xl:grid-cols-7">
                @foreach ($summaryCards as $card)
                    <div class="min-h-28 rounded-lg border border-gray-200 bg-white px-4 py-3 text-gray-800">
                        <div class="text-xs font-medium text-gray-400">{{ $card['label'] }}</div>
                        <div class="mt-5 truncate text-xl font-bold">Rp{{ Number::format($card['amount'], locale: 'id') }}</div>
                    </div>
                @endforeach
            </section>

            <section class="min-w-0">
                <div class="mb-3 flex flex-wrap items-center justify-between gap-3">
                    <h2 class="text-xl font-bold text-gray-900">Expenses</h2>

                    <button x-on:click="createExpense = true" class="flex items-center gap-2 rounded-lg bg-green-500 px-3 py-2 text-sm font-semibold text-white hover:bg-green-600">
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-5">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 4.5v15m7.5-7.5h-15" />
                        </svg>
                        <span>New Expense</span>
                    </button>
                </div>

                <livewire:showExpense :activeBudget="$activeBudget" :key="'expenses-'.$activeBudget->id" />
            </section>

            <livewire:createExpense @saved="$refresh" :activeBudget="$activeBudget" :key="'create-expense-'.$activeBudget->id" />
        @else
            <section class="rounded-lg border border-dashed border-gray-300 px-6 py-12 text-center">
                <div class="text-lg font-semibold text-gray-900">No budget yet</div>
                <p class="mt-1 text-sm text-gray-500">Create your first budget to start tracking expenses.</p>
                <button x-on:click="createBudget = true" class="mt-4 rounded-lg bg-green-500 px-4 py-2 text-sm font-semibold text-white hover:bg-green-600">
                    New Budget
                </button>
            </section>
        @endif
    </main>
</div>
