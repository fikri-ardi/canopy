<div x-show="createExpense" x-cloak x-transition x-on:click.self="createExpense = false" class="modal-backdrop">
    <div class="modal-panel">
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
                    <input wire:model="name" x-on:blur="advanceExpenseName($event.target.value)" x-on:keydown.enter.prevent="advanceExpenseName($event.target.value)" type="text" name="name" id="expense-name" placeholder="Makan" class="input-field" data-onboarding-target="expense-name">
                    @error('name')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                <div>
                    <label for="expense-amount" class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Amount</label>
                    <input wire:model="amount" x-on:blur="advanceExpenseAmount($event.target.value)" x-on:keydown.enter.prevent="advanceExpenseAmount($event.target.value)" type="text" inputmode="numeric" autocomplete="off" data-number-format="live" name="amount" id="expense-amount" placeholder="300.000" class="input-field" data-onboarding-target="expense-amount">
                    @error('amount')
                        <div class="mt-1 text-xs text-red-500">{{ $message }}</div>
                    @enderror
                </div>

                @if ($labelsReady)
                    <div x-data="{labelMenu: canopyDropdown()}" class="relative">
                        <label class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Label</label>
                        <button x-ref="labelTrigger" type="button" x-on:click.stop="labelMenu.toggle($refs.labelTrigger, $refs.labelMenu)" class="btn-secondary w-full justify-between" data-onboarding-target="expense-label">
                            <span class="truncate">{{ $selectedLabel?->name ?? 'Select label' }}</span>
                            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                            </svg>
                        </button>

                        <template x-teleport="body">
                            <div x-ref="labelMenu" x-show="labelMenu.open" x-cloak x-transition x-bind:style="labelMenu.style" x-on:click.outside="labelMenu.close()" x-on:resize.window="labelMenu.close()" wire:key="create-expense-label-menu" wire:ignore.self class="floating-select-menu">
                                @foreach ($labels as $label)
                                    <button type="button" x-on:click="labelMenu.close(); advanceExpenseChoice('expense-label', 'expense-platform')" wire:click="selectLabel({{ $label->id }})" wire:key="create-expense-label-option-{{ $label->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                        {{ $label->name }}
                                    </button>
                                @endforeach
                            </div>
                        </template>
                    </div>
                @endif

                <div x-data="{platformMenu: canopyDropdown()}" class="relative">
                    <label class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Platform</label>
                    <button x-ref="platformTrigger" type="button" x-on:click.stop="platformMenu.toggle($refs.platformTrigger, $refs.platformMenu)" class="btn-secondary w-full justify-between" data-onboarding-target="expense-platform">
                        <span class="truncate">{{ $selectedPlatform?->name }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <template x-teleport="body">
                        <div x-ref="platformMenu" x-show="platformMenu.open" x-cloak x-transition x-bind:style="platformMenu.style" x-on:click.outside="platformMenu.close()" x-on:resize.window="platformMenu.close()" wire:key="create-expense-platform-menu" wire:ignore.self class="floating-select-menu">
                            @foreach ($platforms as $platform)
                                <button type="button" x-on:click="platformMenu.close(); advanceExpenseChoice('expense-platform', 'expense-status')" wire:click="selectPlatform({{ $platform->id }})" wire:key="create-expense-platform-option-{{ $platform->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                    {{ $platform->name }}
                                </button>
                            @endforeach
                        </div>
                    </template>
                </div>

                <div x-data="{statusMenu: canopyDropdown()}" class="relative">
                    <label class="mb-1 block text-xs font-semibold uppercase text-gray-400 dark:text-slate-500">Status</label>
                    <button x-ref="statusTrigger" type="button" x-on:click.stop="statusMenu.toggle($refs.statusTrigger, $refs.statusMenu)" class="btn-secondary w-full justify-between" data-onboarding-target="expense-status">
                        <span class="truncate">{{ $selectedStatus?->body }}</span>
                        <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                            <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                        </svg>
                    </button>

                    <template x-teleport="body">
                        <div x-ref="statusMenu" x-show="statusMenu.open" x-cloak x-transition x-bind:style="statusMenu.style" x-on:click.outside="statusMenu.close()" x-on:resize.window="statusMenu.close()" wire:key="create-expense-status-menu" wire:ignore.self class="floating-select-menu">
                            @foreach ($statuses as $status)
                                <button type="button" x-on:click="statusMenu.close(); advanceExpenseChoice('expense-status', 'expense-create')" wire:click="selectStatus({{ $status->id }})" wire:key="create-expense-status-option-{{ $status->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                                    {{ $status->body }}
                                </button>
                            @endforeach
                        </div>
                    </template>
                </div>
            </div>

            <div class="flex justify-end gap-2">
                <button type="button" x-on:click="createExpense = false" class="btn-secondary">Cancel</button>
                <button type="submit" class="btn-primary" data-onboarding-target="expense-create">
                    <span>Add Expense</span>
                    <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.8" stroke="currentColor" class="size-4">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M13.5 4.5 21 12m0 0-7.5 7.5M21 12H3" />
                    </svg>
                </button>
            </div>
        </form>
    </div>
</div>
