<div x-show="createExpense" x-cloak x-transition class="modal-backdrop">
    <div x-on:click.away="createExpense = false" class="modal-panel">
        <div class="flex items-center gap-3">
            <span class="icon-box">
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.7" stroke="currentColor" class="size-5">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M20.25 6.375c0 2.278-3.694 4.125-8.25 4.125S3.75 8.653 3.75 6.375m16.5 0c0-2.278-3.694-4.125-8.25-4.125S3.75 4.097 3.75 6.375m16.5 0v11.25c0 2.278-3.694 4.125-8.25 4.125s-8.25-1.847-8.25-4.125V6.375" />
                </svg>
            </span>
            <div>
                <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Create Expense</div>
                <p class="text-sm text-gray-500 dark:text-slate-400">Add a transaction to this budget.</p>
            </div>
        </div>

        <form class="mt-5 space-y-5" wire:submit="store">
            <div class="space-y-3">
                <div>
                    <label for="expense-name" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Expense</label>
                    <input wire:model="name" type="text" name="name" id="expense-name" placeholder="Makan" class="input-field">
                    @error('name')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="expense-amount" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Amount</label>
                    <input wire:model="amount" type="number" min="0" name="amount" id="expense-amount" placeholder="300000" class="input-field">
                    @error('amount')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                @if ($labelsReady)
                    <div x-data="{selectLabel: false}" class="relative">
                        <label class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Label</label>
                        <button type="button" x-on:click="selectLabel = true" class="btn-secondary w-full justify-between">
                            <span class="truncate">{{ $selectedLabel?->name ?? 'Select label' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <div x-show="selectLabel" x-cloak x-on:click.away="selectLabel = false" x-transition class="select-menu">
                            @foreach ($labels as $label)
                                <button type="button" x-on:click="selectLabel = false" wire:click="selectLabel({{ $label->id }})" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                    {{ $label->name }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @endif

                <div x-data="{selectPlatform: false}" class="relative">
                    <label class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Platform</label>
                    <button type="button" x-on:click="selectPlatform = true" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $selectedPlatform?->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div x-show="selectPlatform" x-cloak x-on:click.away="selectPlatform = false" x-transition class="select-menu">
                        @foreach ($platforms as $platform)
                            <button type="button" x-on:click="selectPlatform = false" wire:click="selectPlatform({{ $platform->id }})" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                {{ $platform->name }}
                            </button>
                        @endforeach
                    </div>
                </div>

                <div x-data="{selectStatus: false}" class="relative">
                    <label class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Status</label>
                    <button type="button" x-on:click="selectStatus = true" class="btn-secondary w-full justify-between">
                        <span class="truncate">{{ $selectedStatus?->body }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <div x-show="selectStatus" x-cloak x-on:click.away="selectStatus = false" x-transition class="select-menu">
                        @foreach ($statuses as $status)
                            <button type="button" x-on:click="selectStatus = false" wire:click="selectStatus({{ $status->id }})" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                {{ $status->body }}
                            </button>
                        @endforeach
                    </div>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" x-on:click="createExpense = false" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary">
                    <span>Add Expense</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
