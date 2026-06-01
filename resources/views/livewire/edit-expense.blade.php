<tr
    x-data="{deleteExpense: false, labelMenu: canopyDropdown(), platformMenu: canopyDropdown(), statusMenu: canopyDropdown()}"
    class="bg-white text-gray-800 transition hover:bg-gray-50 dark:bg-slate-900 dark:text-slate-100 dark:hover:bg-slate-800/60"
>
    <td class="px-2 py-2 text-center text-xs text-gray-400 dark:text-slate-500 sm:px-3 sm:py-3 sm:text-sm">{{ $iteration }}</td>
    <td class="expense-cell">
        <div class="expense-name-field">
            <input wire:model.blur="name" type="text" class="expense-name-input" />
        </div>
        @error('name')
            <div class="px-2 text-xs text-red-500">{{ $message }}</div>
        @enderror
    </td>
    <td class="expense-cell" data-tone="{{ $this->amountToneClass() }}">
        <div class="expense-field expense-amount-field {{ $this->amountToneClass() }}" style="{{ $this->toneStyle($this->amountToneClass()) }}">
            <input
                wire:model.blur="amount"
                type="text"
                inputmode="numeric"
                autocomplete="off"
                class="expense-amount-input"
                data-number-format="live"
            />
        </div>
        @error('amount')
            <div class="px-2 text-xs text-red-500">{{ $message }}</div>
        @enderror
    </td>
    <td class="expense-cell" data-tone="{{ $this->labelToneClass() }}">
        @if ($labelsReady)
            <button x-ref="labelTrigger" type="button" x-on:click.stop="labelMenu.toggle($refs.labelTrigger, $refs.labelMenu)" class="expense-field expense-select-field {{ $this->labelToneClass() }}" style="{{ $this->toneStyle($this->labelToneClass()) }}">
                <span class="truncate">{{ $spend->label?->name ?? 'Unlabeled' }}</span>
                <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
                </svg>
            </button>
            <template x-teleport="body">
                <div x-ref="labelMenu" x-show="labelMenu.open" x-cloak x-transition x-bind:style="labelMenu.style" x-on:click.outside="labelMenu.close()" x-on:resize.window="labelMenu.close()" wire:key="edit-expense-{{ $spend->id }}-label-menu" wire:ignore.self class="floating-select-menu">
                    <button type="button" x-on:click="labelMenu.close()" wire:click="$set('label_id', '')" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">Unlabeled</button>
                    @foreach ($labels as $label)
                        <button type="button" x-on:click="labelMenu.close()" wire:click="$set('label_id', {{ $label->id }})" wire:key="edit-expense-{{ $spend->id }}-label-option-{{ $label->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                            {{ $label->name }}
                        </button>
                    @endforeach
                </div>
            </template>
        @else
            <span class="expense-field border-l-gray-300 text-gray-400 dark:border-l-slate-600">Pending migration</span>
        @endif
    </td>
    <td class="expense-cell" data-tone="{{ $this->platformToneClass() }}">
        <button x-ref="platformTrigger" type="button" x-on:click.stop="platformMenu.toggle($refs.platformTrigger, $refs.platformMenu)" class="expense-field expense-select-field {{ $this->platformToneClass() }}" style="{{ $this->toneStyle($this->platformToneClass()) }}">
            <span class="truncate">{{ $spend->platform->name }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>
        <template x-teleport="body">
            <div x-ref="platformMenu" x-show="platformMenu.open" x-cloak x-transition x-bind:style="platformMenu.style" x-on:click.outside="platformMenu.close()" x-on:resize.window="platformMenu.close()" wire:key="edit-expense-{{ $spend->id }}-platform-menu" wire:ignore.self class="floating-select-menu">
                @foreach ($platforms as $platform)
                    <button type="button" x-on:click="platformMenu.close()" wire:click="$set('platform_id', {{ $platform->id }})" wire:key="edit-expense-{{ $spend->id }}-platform-option-{{ $platform->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        {{ $platform->name }}
                    </button>
                @endforeach
            </div>
        </template>
    </td>
    <td class="expense-cell" data-tone="{{ $this->statusToneClass() }}">
        <button x-ref="statusTrigger" type="button" x-on:click.stop="statusMenu.toggle($refs.statusTrigger, $refs.statusMenu)" class="expense-field expense-select-field {{ $this->statusToneClass() }}" style="{{ $this->toneStyle($this->statusToneClass()) }}">
            <span class="truncate">{{ $spend->status->body }}</span>
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor" class="size-4 shrink-0">
                <path stroke-linecap="round" stroke-linejoin="round" d="m19.5 8.25-7.5 7.5-7.5-7.5" />
            </svg>
        </button>
        <template x-teleport="body">
            <div x-ref="statusMenu" x-show="statusMenu.open" x-cloak x-transition x-bind:style="statusMenu.style" x-on:click.outside="statusMenu.close()" x-on:resize.window="statusMenu.close()" wire:key="edit-expense-{{ $spend->id }}-status-menu" wire:ignore.self class="floating-select-menu">
                @foreach ($statuses as $status)
                    <button type="button" x-on:click="statusMenu.close()" wire:click="$set('status_id', {{ $status->id }})" wire:key="edit-expense-{{ $spend->id }}-status-option-{{ $status->id }}" class="w-full px-3 py-2 text-left text-sm text-gray-700 hover:bg-gray-100 dark:text-slate-200 dark:hover:bg-slate-800">
                        {{ $status->body }}
                    </button>
                @endforeach
            </div>
        </template>
    </td>
    <td class="p-1.5 text-center sm:p-2">
        <button
            type="button"
            x-on:click="deleteExpense = true"
            class="inline-flex size-8 items-center justify-center rounded-md bg-red-50 text-red-600 transition hover:bg-red-100 dark:bg-red-500/10 dark:text-red-300 dark:hover:bg-red-500/20 sm:size-9 sm:rounded-lg"
            aria-label="Delete expense"
        >
            <svg xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor" class="size-4">
                <path stroke-linecap="round" stroke-linejoin="round" d="m14.74 9-.346 9m-4.788 0L9.26 9m9.968-3.21c.342.052.682.107 1.022.166m-1.022-.165L18.16 19.673A2.25 2.25 0 0 1 15.916 21.75H8.084a2.25 2.25 0 0 1-2.244-2.077L4.772 5.79m14.456 0a48.108 48.108 0 0 0-3.478-.397m-12 .562c.34-.059.68-.114 1.022-.165m0 0a48.11 48.11 0 0 1 3.478-.397m7.5 0v-.916c0-1.18-.91-2.164-2.09-2.201a51.964 51.964 0 0 0-3.32 0c-1.18.037-2.09 1.022-2.09 2.201v.916m7.5 0a49.058 49.058 0 0 0-7.5 0" />
            </svg>
        </button>

        <template x-teleport="body">
            <div x-show="deleteExpense" x-cloak x-transition class="modal-backdrop">
                <div x-on:click.away="deleteExpense = false" class="modal-panel text-left">
                    <div class="text-lg font-semibold text-gray-950 dark:text-slate-50">Delete Expense</div>
                    <p class="mt-3 text-sm text-gray-500 dark:text-slate-400">
                        Delete <span class="font-semibold text-gray-950 dark:text-slate-50">{{ $name }}</span>? This action cannot be undone.
                    </p>

                    <div class="mt-6 flex justify-end gap-2">
                        <button type="button" x-on:click="deleteExpense = false" class="btn-secondary">
                            Cancel
                        </button>
                        <button type="button" x-on:click="deleteExpense = false" wire:click="delete" class="btn-danger">
                            Delete
                        </button>
                    </div>
                </div>
            </div>
        </template>
    </td>
</tr>
