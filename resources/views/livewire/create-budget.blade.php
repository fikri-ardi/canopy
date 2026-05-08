<div x-show="createBudget" x-cloak x-transition class="modal-backdrop">
    <div x-on:click.away="createBudget = false" class="modal-panel">
        <div class="flex items-center gap-3">
            <span class="icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12h3.75M9 15h3.75M9 18h3.75m3 .75H18a2.25 2.25 0 0 0 2.25-2.25V6.108c0-1.135-.845-2.098-1.976-2.192a48.424 48.424 0 0 0-1.123-.08m-5.801 0A2.251 2.251 0 0 1 13.5 2.25H15c1.012 0 1.867.668 2.15 1.586m-5.8 0c-.376.023-.75.05-1.124.08C9.095 4.01 8.25 4.973 8.25 6.108V8.25" />
                </svg>
            </span>
            <div>
                <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Create Budget</div>
                <p class="text-sm text-gray-500 dark:text-slate-400">Start a new income plan.</p>
            </div>
        </div>

        <form class="mt-5 space-y-5" wire:submit="store">
            <div class="space-y-3">
                <div>
                    <label for="budget-name" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Name</label>
                    <input wire:model="name" type="text" name="name" id="budget-name" placeholder="Monthly plan" class="input-field">
                    @error('name')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>
                <div>
                    <label for="budget-income" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Income</label>
                    <input wire:model="income" type="number" min="0" name="income" id="budget-income" placeholder="2000000" class="input-field">
                    @error('income')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" x-on:click="createBudget = false" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">
                    <span>Create</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
